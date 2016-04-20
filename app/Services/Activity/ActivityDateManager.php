<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActivityDateManager
 * @package app\Services\Activity
 */
class ActivityDateManager
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
        $this->iatiActivtyDateRepo = $version->getActivityElement()->getActivityDate()->getRepository();
    }

    /**
     * updates Activity Date
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->iatiActivtyDateRepo->update($activityDetails, $activity);
            $this->log->info(
                'Activity Date Updated!',
                ['for ' => $activity['activity_date']]
            );
            $this->log->activity(
                "activity.activity_date_updated",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->log->error($exception, ['ActivityDate' => $activityDetails]);
        }

        return false;
    }

    /**
     * @param $id
     * @return Model
     */
    public function getActivityDateData($id)
    {
        return $this->iatiActivtyDateRepo->getActivityDateData($id);
    }
}
