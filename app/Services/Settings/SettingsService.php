<?php namespace App\Services\Settings;

use App\Exceptions\Aidstream\Workflow\PublisherNotFoundException;
use App\Models\Organization\Organization;
use App\Services\Publisher\Publisher;
use App\Services\Settings\Segmentation\SegmentationInterface;
use App\Services\Workflow\DataProvider\OrganizationDataProvider;
use App\Services\Workflow\DataProvider\PublishedFilesDataProvider;
use App\Services\Xml\Providers\XmlServiceProvider;
use Illuminate\Database\DatabaseManager;
use Psr\Log\LoggerInterface;

/**
 * Class SettingsService
 * @package App\Services\Settings
 */
class SettingsService
{
    /**
     * @var bool
     */
    protected $shouldPublish = false;

    /**
     * @var SegmentationInterface
     */
    protected $segmentationInterface;

    /**
     * @var Publisher
     */
    protected $publisher;

    /**
     * @var XmlServiceProvider
     */
    protected $xmlServiceProvider;

    /**
     * @var OrganizationDataProvider
     */
    protected $organizationDataProvider;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * SettingsService constructor.
     * @param SegmentationInterface    $segmentationInterface
     * @param Publisher                $publisher
     * @param XmlServiceProvider       $xmlServiceProvider
     * @param OrganizationDataProvider $organizationDataProvider
     * @param DatabaseManager          $databaseManager
     * @param LoggerInterface          $logger
     */
    public function __construct(
        SegmentationInterface $segmentationInterface,
        Publisher $publisher,
        XmlServiceProvider $xmlServiceProvider,
        OrganizationDataProvider $organizationDataProvider,
        DatabaseManager $databaseManager,
        LoggerInterface $logger
    ) {
        $this->segmentationInterface    = $segmentationInterface;
        $this->publisher                = $publisher;
        $this->xmlServiceProvider       = $xmlServiceProvider;
        $this->organizationDataProvider = $organizationDataProvider;
        $this->logger                   = $logger;
        $this->databaseManager          = $databaseManager;
    }

    /**
     * Check if the segmentation has changed.
     * @param $organizationId
     * @param $settings
     * @return bool
     */
    public function hasSegmentationChanged($organizationId, $settings)
    {
        return $this->segmentationInterface->detectSegmentationChangeFor(
            $this->organizationDataProvider->find($organizationId),
            $this->segmentationInterface->extractPublishingType($settings)
        );
    }

    /**
     * Get the Change Log for the Segmentation changes.
     * @param       $organizationId
     * @param array $settings
     * @return mixed
     */
    public function getChangeLog($organizationId, array $settings)
    {
        $organization = $this->organizationDataProvider->find($organizationId);

        return $this->segmentationInterface->getChanges(
            $organization,
            $this->segmentationInterface->extractPublishingType($settings),
            $this->organizationDataProvider->getCurrentStatus($organization->publishedFiles)
        );
    }

    /**
     * Change Segmentation for an Organization.
     * @param array $details
     * @return array|bool
     */
    public function changeSegmentation(array $details)
    {
        try {
            $organizationId = $details['organizationId'];
            $organization   = $this->organizationDataProvider->find($organizationId);
            $version        = $organization->settings->version;
            $changes        = json_decode($details['changes'], true);

            $this->databaseManager->beginTransaction();

            $this->xmlServiceProvider->initializeGenerator($version);

            if (!empty($changes['changes'])) {
                foreach ($changes['changes'] as $filename => $activities) {
                    $this->xmlServiceProvider->generateXmlFiles($activities['included_activities'], $filename)
                                             ->save($filename, $organizationId, $activities['included_activities']);
                }

                foreach ($changes['previous'] as $filename => $data) {
                    $this->organizationDataProvider->deleteOldData($filename, $organization->id);
                }

                $this->publishSegmentationChanges($organization, $details);
            }

            $this->databaseManager->commit();

            $this->logger->info(
                'Segmentation successfully changed.',
                [
                    'changes' => $changes,
                    'by_user' => auth()->user()->name
                ]
            );

            return true;
        } catch (\Exception $exception) {
            $this->databaseManager->rollback();

            $context = [
                'changes' => $details['changes'],
                'by_user' => auth()->user()->getNameAttribute(),
                'trace'   => $exception->getTraceAsString()
            ];

            if ($exception instanceof PublisherNotFoundException) {
                $this->logger->error(sprintf('Could not publish to registry due to %s', $exception->getMessage()), $context);

                return false;
            }

            $this->logger->error(sprintf('Error while changing segmentation due to %s', $exception->getMessage()), $context);

            return null;
        }

    }

    /**
     * Check if the previous file was published to registry.
     * @param array $changes
     * @return bool
     */
    protected function previousFileWasAlreadyPublished(array $changes)
    {
        foreach ($changes['previous'] as $previous) {
            if ($previous['published_status']) {
                $this->shouldPublish = true;
            }
        }

        return $this->shouldPublish;
    }

    /**
     * Publish segmentation changes to the IATI Registry.
     * @param Organization $organization
     * @param              $details
     * @throws \App\Exceptions\Aidstream\Workflow\PublisherNotFoundException
     * @throws \Exception
     * @internal param $organizationId
     */
    protected function publishSegmentationChanges(Organization $organization, array $details)
    {
        $changes              = json_decode($details['changes'], true);
        $settings             = json_decode($details['settings'], true);
        $organizationSettings = $organization->settings->toArray();
        $autoPublish          = $settings['publish_files'];

        if ($autoPublish === 'yes') {
            if ($this->previousFileWasAlreadyPublished($changes)) {
                $this->publisher->unlink(getVal($organizationSettings, ['registry_info']), $changes);

                $this->organizationDataProvider->unsetPublishedFlag($changes);

                $published = true;
                $this->logger->info(
                    'Successfully unlinked old file(s) from the IATI Registry.',
                    [
                        'changes'   => $changes,
                        'publisher' => auth()->user()->getNameAttribute()
                    ]
                );
            }

            $this->publisher->publish(
                getVal($organizationSettings, ['registry_info'], []),
                $organization,
                $this->segmentationInterface->extractPublishingType($settings),
                $changes
            );

            if ($this->shouldPublish || isset($published)) {
                $this->organizationDataProvider->updateStatus($changes, $organization->id);
            }
        }
    }
}
