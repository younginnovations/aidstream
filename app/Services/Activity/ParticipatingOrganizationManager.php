<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ParticipatingOrganizationManager
 * @package app\Services\Activity
 */
class ParticipatingOrganizationManager
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
        $this->auth                 = $auth;
        $this->log                  = $log;
        $this->participatingOrgRepo = $version->getActivityElement()
                                              ->getParticipatingOrganization()
                                              ->getRepository();
    }

    /**
     * updates Activity Participating Organization
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->participatingOrgRepo->update($activityDetails, $activity);
            $this->log->info(
                'Activity Participating Organization updated!',
                ['for' => $activity->participating_organization]
            );
            $this->log->activity(
                "activity.participating_organization",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->log->error(
                sprintf('Activity Participating Organization could not be updated due to %s', $exception->getMessage()),
                [
                    'ParticipatingOrganization' => $activityDetails,
                    'trace'                     => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return Model
     */
    public function getParticipatingOrganizationData($id)
    {
        return $this->participatingOrgRepo->getParticipatingOrganizationData($id);
    }
}
