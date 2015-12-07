<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;

/**
 * Class RecipientRegionManager
 * @package app\Services\Activity
 */
class RecipientRegionManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Log
     */
    protected $log;
    /**
     * @var Version
     */
    protected $version;

    /**
     * @param Version $version
     * @param Log     $log
     * @param Guard   $auth
     */
    public function __construct(Version $version, Log $log, Guard $auth)
    {
        $this->auth                = $auth;
        $this->log                 = $log;
        $this->recipientRegionRepo = $version->getActivityElement()->getRecipientRegion()->getRepository();
    }

    /**
     * updates Activity recipient region
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->recipientRegionRepo->update($activityDetails, $activity);
            $this->log->info(
                'Activity Recipient Region Updated!',
                ['for ' => $activity->recipient_region]
            );
            $this->log->activity(
                "activity.recipient_region_updated",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->log->error(
                sprintf('Activity Recipient Region could not be updated due to %s', $exception->getMessage()),
                [
                    'recipientRegion' => $activityDetails,
                    'trace'           => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return model
     */
    public function getRecipientRegionData($id)
    {
        return $this->recipientRegionRepo->getRecipientRegionData($id);
    }
}
