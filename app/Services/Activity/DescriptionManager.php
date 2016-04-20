<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DescriptionManager
 * @package app\Services\Activity
 */
class DescriptionManager
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
        $this->iatiDescriptionRepo = $version->getActivityElement()->getdescription()->getRepository();
        $this->activityRepo        = $version->getActivityElement()->getRepository();
    }

    /**
     * updates Activity Description
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->iatiDescriptionRepo->update($activityDetails, $activity);
            $this->log->info(
                'Activity Description Updated!',
                ['for ' => $activity['description']]
            );
            $this->log->activity(
                "activity.description_updated",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->log->error($exception, ['Description' => $activityDetails]);
        }

        return false;
    }

    /**
     * @param $id
     * @return Model
     */
    public function getDescriptionData($id)
    {
        return $this->iatiDescriptionRepo->getDescriptionData($id);
    }

    /**
     * @param $id
     * @return Model
     */
    public function getActivityData($id)
    {
        return $this->activityRepo->getActivityData($id);
    }
}
