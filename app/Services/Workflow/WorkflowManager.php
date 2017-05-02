<?php namespace App\Services\Workflow;

use App\Services\PerfectViewer\PerfectViewerManager;
use App\Services\Workflow\Traits\ExceptionParser;
use Exception;
use Psr\Log\LoggerInterface;
use App\Models\Activity\Activity;
use App\Services\Twitter\TwitterAPI;
use App\Services\Publisher\Publisher;
use App\Services\Activity\ActivityManager;
use App\Services\Organization\OrganizationManager;
use App\Services\Xml\Providers\XmlServiceProvider;
use App\Services\Workflow\DataProvider\OrganizationDataProvider;

/**
 * Class WorkflowManager
 * @package App\Services\Workflow
 */
class WorkflowManager
{
    use ExceptionParser;

    /**
     * Status code for Not Authorized Exception.
     */
    const NOT_AUTHORIZED_ERROR_CODE = 403;

    const PACKAGE_NOT_FOUND_ERROR_CODE = 404;

    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * @var XmlServiceProvider
     */
    protected $xmlServiceProvider;

    /**
     * @var
     */
    protected $activity;

    /**
     * @var Publisher
     */
    protected $publisher;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var TwitterAPI
     */
    protected $twitter;

    /**
     * @var PerfectViewerManager
     */
    protected $perfectActivity;
    /**
     * @var SegmentationChangeHandler
     */
    protected $segmentationChangeHandler;

    /**
     * WorkflowManager constructor.
     * @param OrganizationManager       $organizationManager
     * @param ActivityManager           $activityManager
     * @param XmlServiceProvider        $xmlServiceProvider
     * @param OrganizationDataProvider  $organizationDataProvider
     * @param Publisher                 $publisher
     * @param LoggerInterface           $logger
     * @param TwitterAPI                $twitter
     * @param SegmentationChangeHandler $segmentationChangeHandler
     * @param PerfectViewerManager      $perfectActivityViewerManager
     */
    public function __construct(
        OrganizationManager $organizationManager,
        ActivityManager $activityManager,
        XmlServiceProvider $xmlServiceProvider,
        OrganizationDataProvider $organizationDataProvider,
        Publisher $publisher,
        LoggerInterface $logger,
        TwitterAPI $twitter,
        SegmentationChangeHandler $segmentationChangeHandler,
        PerfectViewerManager $perfectActivityViewerManager
    ) {
        $this->organizationManager       = $organizationManager;
        $this->activityManager           = $activityManager;
        $this->xmlServiceProvider        = $xmlServiceProvider;
        $this->organizationDataProvider  = $organizationDataProvider;
        $this->publisher                 = $publisher;
        $this->logger                    = $logger;
        $this->twitter                   = $twitter;
        $this->perfectActivity           = $perfectActivityViewerManager;
        $this->segmentationChangeHandler = $segmentationChangeHandler;
    }

    /**
     * Find an Activity with a specific id.
     * @param $id
     * @return Activity
     */
    public function findActivity($id)
    {
        return $this->organizationDataProvider->findActivity($id);
    }

    /**
     * Validate Activity against an Activity Xml Schema.
     * @param $activity
     * @return mixed
     */
    public function validate($activity)
    {
        $version             = $activity->organization->settings->version;
        $organizationElement = $this->organizationManager->getOrganizationElement();
        $activityElement     = $this->activityManager->getActivityElement();

        return $this->xmlServiceProvider->initializeValidator($version)->validate($activity, $organizationElement, $activityElement);
    }

    /**
     * @param $data
     * @param $activity
     * @return mixed
     */
    public function update(array $data, Activity $activity)
    {
        return $this->activityManager->updateStatus($data, $activity);
    }

    /**
     * Publish an Activity.
     *
     * If the auto-publish option is set, the Activity data is published into the IATI Registry.
     * @param $activity
     * @param $details
     * @return array
     */
    public function publish($activity, array $details)
    {
        try {
            $organization        = $activity->organization;
            $settings            = $organization->settings;
            $version             = $settings->version;
            $linked              = true;
            $publishedActivities = $organization->publishedFiles;
            $this->xmlServiceProvider->initializeGenerator($version);

            if ($this->shouldChangeSegmentation($settings, $publishedActivities)) {
                $this->segmentationChangeHandler->changes($activity, $publishedActivities, $organization, $settings, $this->xmlServiceProvider, $this->publisher);
            }

            $this->xmlServiceProvider->generate(
                $activity,
                $this->organizationManager->getOrganizationElement(),
                $this->activityManager->getActivityElement()
            );


            if (getVal($settings['registry_info'], [0, 'publish_files']) == 'yes') {
                $this->publisher->publishFile(
                    $organization->settings['registry_info'],
                    $this->organizationDataProvider->fileBeingPublished($activity->id),
                    $organization,
                    $organization->settings->publishing_type
                );

                $activity->published_to_registry = 1;
                $activity->save();

                $this->activityManager->activityInRegistry($activity);

                if ($this->shouldPostOnTwitter($settings)) {
                    $this->twitter->post($organization->settings, $organization);
                }
            } else {
                $linked = false;
            }

            $this->perfectActivity->createSnapshot($activity);

            $this->update($details, $activity);

            return ['status' => true, 'linked' => $linked];
        } catch (Exception $exception) {
            $this->logger->error($exception, ['trace' => $exception->getTraceAsString()]);

            return $this->parse($exception);
        }
    }

    protected function shouldPostOnTwitter($settings)
    {
        return ($settings->post_on_twitter) ? true : false;
    }

    protected function shouldChangeSegmentation($settings, $publishedActivities)
    {
        if ($settings->publishing_type == 'segmented' && count($publishedActivities) > 0) {
            return true;
        }

        return false;
    }
}

