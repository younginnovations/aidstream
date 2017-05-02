<?php namespace App\Services\Workflow;


use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;
use App\Models\Settings;
use App\Services\Publisher\Publisher;
use App\Services\Publisher\Traits\RegistryApiInvoker;
use App\Services\Xml\Providers\XmlServiceProvider;
use Exception;

/**
 * Class SegmentationChangeHandler
 * @package App\Services\Workflow
 */
class SegmentationChangeHandler
{
    use RegistryApiInvoker;

    /**
     * Make changes when the settings is segmented and the recipient region or country is changed.
     * The activity whose recipient country or region is changed is moved to new row and deleted from previous row.
     * If the activity was already published then new xml file is generated for the previous file.
     *
     * @param Activity           $activity
     * @param                    $publishedActivities
     * @param Organization       $organization
     * @param Settings           $settings
     * @param XmlServiceProvider $xmlServiceProvider
     * @param Publisher          $publisher
     * @return bool
     * @throws Exception
     */
    public function changes(Activity $activity, $publishedActivities, Organization $organization, Settings $settings, XmlServiceProvider $xmlServiceProvider, Publisher $publisher)
    {
        $previousPublishedActivity = $this->returnPublishedActivity($activity->id, $publishedActivities);

        if (!$previousPublishedActivity) {
            return false;
        }

        $expectedFilename = sprintf('%s.xml', segmentedXmlFile($activity, getVal($settings->registry_info, [0, 'publisher_id'])));
        $previousFilename = $previousPublishedActivity->filename;

        if ($previousFilename == $expectedFilename) {
            return false;
        }

        $includedActivities                              = $this->removeCurrentActivityFromPublished($previousPublishedActivity, $activity->id);
        $previousPublishedActivity->published_activities = $includedActivities;

        try {
            if (empty($includedActivities)) {
                $previousPublishedActivity->delete();
            } else {
                $xmlServiceProvider->generateXmlFiles($includedActivities, $previousFilename);
                $previousPublishedActivity->save();
            }

            if ($previousPublishedActivity->published_to_register == 1) {
                (!empty($includedActivities)) ?
                    $publisher->publishFile(
                        $settings->registry_info,
                        $previousPublishedActivity,
                        $organization,
                        $settings->publishing_type
                    )
                    : $this->unlink(getVal($settings->registry_info, [0, 'api_id']), $this->extractPackage($previousFilename));

            }

            return true;

        } catch (Exception $exception) {
            throw  $exception;
        }
    }

    /**
     * Remove the provided activity from the published activity table.
     * Return the remaining activities.
     *
     * @param $previousPublishedActivity
     * @param $activityId
     * @return array
     */
    protected function removeCurrentActivityFromPublished($previousPublishedActivity, $activityId)
    {
        $includedActivities = array_flip($previousPublishedActivity->published_activities);
        unset($includedActivities[$previousPublishedActivity->extractActivityId()[$activityId]]);

        return array_flip($includedActivities);
    }

    /**
     * Delete the provided package from Registry.
     *
     * @param $apiId
     * @param $packageId
     */
    protected function unlink($apiId, $packageId)
    {
        $this->deletePackage($apiId, $packageId);
    }


    /**
     * Returns model of published Activity.
     *
     * @param $activityId
     * @param $publishedActivities
     * @return bool
     */
    protected function returnPublishedActivity($activityId, $publishedActivities)
    {
        foreach ($publishedActivities as $index => $publishedActivity) {
            if (array_key_exists($activityId, $publishedActivity->extractActivityId())) {
                return $publishedActivity;
            }
        }

        return false;
    }
}

