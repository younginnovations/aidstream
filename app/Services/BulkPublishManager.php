<?php namespace App\Services;


use App\Core\V201\Repositories\Activity\ActivityRepository;
use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Core\V201\Repositories\SettingsRepository;
use App\Services\Publisher\Publisher;
use App\Services\Twitter\TwitterAPI;
use App\Services\Workflow\Traits\ExceptionParser;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class BulkPublishManager
 * @package App\Services
 */
class BulkPublishManager
{
    use ExceptionParser;

    /**
     * Status code for Not Authorized Exception.
     */
    const NOT_AUTHORIZED_ERROR_CODE = 403;

    /**
     *
     */
    const PACKAGE_NOT_FOUND_ERROR_CODE = 404;

    /**
     * @var Publisher
     */
    protected $publisher;
    /**
     * @var ActivityRepository
     */
    protected $activityRepository;
    /**
     * @var SettingsRepository
     */
    protected $settingsRepository;
    /**
     * @var OrganizationRepository
     */
    protected $organizationRepository;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var TwitterAPI
     */
    protected $twitter;

    /**
     * BulkPublishManager constructor.
     * @param Publisher              $publisher
     * @param ActivityRepository     $activityRepository
     * @param SettingsRepository     $settingsRepository
     * @param OrganizationRepository $organizationRepository
     * @param LoggerInterface        $logger
     * @param TwitterAPI             $twitter
     */
    public function __construct(
        Publisher $publisher,
        ActivityRepository $activityRepository,
        SettingsRepository $settingsRepository,
        OrganizationRepository $organizationRepository,
        LoggerInterface $logger,
        TwitterAPI $twitter
    ) {
        $this->publisher              = $publisher;
        $this->activityRepository     = $activityRepository;
        $this->settingsRepository     = $settingsRepository;
        $this->organizationRepository = $organizationRepository;
        $this->logger                 = $logger;
        $this->twitter                = $twitter;
    }

    /**
     * Bulk publish the selected activities to the IATI Registry.
     *
     * @param $organizationId
     * @param $files
     * @return array
     */
    public function bulkPublishActivity($organizationId, $files)
    {
        try {
            $organization = $this->getOrganization($organizationId);
            $settings     = $this->getSettings($organizationId)->toArray();

            $registryInfo   = getVal($settings, ['registry_info'], []);
            $publishingType = getVal($settings, ['publishing_type']);

            foreach ($files as $index => $publishedActivityId) {
                $publishedActivity = $this->getPublishedActivity($organization, $publishedActivityId);
                $this->publisher->publishFile($registryInfo, $publishedActivity, $organization, $publishingType);
                $this->updateActivityStatus($publishedActivity->extractActivityId());
            }

            if ($this->shouldPostOnTwitter($settings)) {
                $this->twitter->post($settings, $organization);
            }

            return ['status' => true];

        } catch (Exception $exception) {
            $this->logger->error($exception, ['trace' => $exception->getTraceAsString()]);

            return $this->parse($exception);
        }
    }

    /**
     * Publish the selected organization to IATI Registry.
     *
     * @param $organizationId
     * @param $files
     * @return array
     */
    public function bulkPublishOrganization($organizationId, $files)
    {
        try {
            $organization = $this->getOrganization($organizationId);
            $settings     = $this->getSettings($organizationId)->toArray();

            $registryInfo   = getVal($settings, ['registry_info'], []);
            $publishingType = getVal($settings, ['publishing_type']);

            foreach ($files as $index => $organizationId) {
                $publishedOrganization = $this->getPublishedOrganization($organization, $organizationId);
                $this->publisher->publishFile($registryInfo, $publishedOrganization, $organization, $publishingType);
            }

            return ['status' => true];

        } catch (Exception $exception) {
            $this->logger->error($exception, ['trace' => $exception->getTraceAsString()]);

            return $this->parse($exception);
        }
    }

    /**
     * Returns organization model.
     *
     * @param $id
     * @return \App\Core\V201\Repositories\Organization\model
     */
    protected function getOrganization($id)
    {
        return $this->organizationRepository->getOrganization($id);
    }

    /**
     * Returns activity model.
     *
     * @param $id
     * @return \App\Core\V201\Repositories\Activity\model
     */
    protected function getActivity($id)
    {
        return $this->activityRepository->getActivityData($id);
    }

    /**
     * @param $organizationId
     * @return mixed
     */
    protected function getSettings($organizationId)
    {
        return $this->settingsRepository->getSettings($organizationId);
    }

    /**
     * Returns published activity model.
     *
     * @param $organization
     * @param $id
     * @return mixed
     */
    protected function getPublishedActivity($organization, $id)
    {
        return $organization->publishedFiles()->where('id', $id)->first();
    }

    /**
     * Returns published organization data model.
     *
     * @param $organization
     * @param $id
     * @return mixed
     */
    protected function getPublishedOrganization($organization, $id)
    {
        return $organization->organizationPublished()->where('id', $id)->first();
    }

    /**
     * Update the published status of the activity.
     *
     * Update the activity status of the organization.
     *
     * @param $activities
     */
    protected function updateActivityStatus($activities)
    {
        foreach ($activities as $activityId => $activity) {
            $dbActivity                        = $this->getActivity($activityId);
            $dbActivity->published_to_registry = 1;
            $dbActivity->save();
        }
    }

    /**
     * Checks if post on twitter is set on or off.
     *
     * @param $settings
     * @return bool
     */
    protected function shouldPostOnTwitter($settings)
    {
        return (getVal($settings, ['post_on_twitter'])) ? true : false;
    }
}

