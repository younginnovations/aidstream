<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;

class ActivityStatus
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
    protected $activityStatusRepo;

    /**
     * @param Version $version
     * @param Log     $log
     * @param Guard   $auth
     */
    function __construct(Version $version, Log $log, Guard $auth)
    {
        $this->activityStatusRepo = $version->getActivityElement()->getActivityStatus()->getRepository();
        $this->auth               = $auth;
        $this->log                = $log;
    }

    /**
     * update activity Status
     * @param array    $input
     * @param Activity $activity
     * @return bool
     */
    public function update(array $input, Activity $activity)
    {
        try {
            $this->activityStatusRepo->update($input, $activity);
            $this->log->info(
                'Activity Status  Updated!',
                ['for ' => $activity['activity_status']]
            );
            $this->log->activity(
                "activity.activity_status_updated",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->log->error(
                sprintf('Activity Status could not be updated due to %s', $exception->getMessage()),
                [
                    'ActivityStatus' => $input,
                    'trace'          => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * Get the Activity Status data for Activity with the given id.
     * @param $id
     * @return Model
     */
    public function getActivityStatusData($id)
    {
        return $this->activityStatusRepo->getActivityStatusData($id);
    }
}
