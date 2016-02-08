<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Mockery\CountValidator\Exception;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class PolicyMarkerManager
 * @package App\Services\Activity
 */
class PolicyMarkerManager
{
    /**
     * @var DbLogger
     */
    protected $dbLogger;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Version  $version
     * @param DbLogger $dbLogger
     * @param Guard    $auth
     * @param Logger   $logger
     */
    public function __construct(Version $version, DbLogger $dbLogger, Guard $auth, Logger $logger)
    {
        $this->auth                 = $auth;
        $this->dbLogger             = $dbLogger;
        $this->logger               = $logger;
        $this->iatiPolicyMarkerRepo = $version->getActivityElement()->getPolicyMarker()->getRepository();
    }

    /**
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->iatiPolicyMarkerRepo->update($activityDetails, $activity);
            $this->logger->info(
                'Policy Marker Updated!',
                ['for' => $activity->policy_marker]
            );
            $this->dbLogger->activity(
                "activity.policy_marker_updated",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Policy Marker could not be updated due to %s', $exception->getMessage()),
                [
                    'policyMarker' => $activityDetails,
                    'trace'        => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPolicyMarkerData($id)
    {
        return $this->iatiPolicyMarkerRepo->getPolicyMarkerData($id);
    }
}
