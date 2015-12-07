<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Mockery\CountValidator\Exception;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class PolicyMakerManager
 * @package App\Services\Activity
 */
class PolicyMakerManager
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
        $this->auth                = $auth;
        $this->dbLogger            = $dbLogger;
        $this->logger              = $logger;
        $this->iatiPolicyMakerRepo = $version->getActivityElement()->getPolicyMaker()->getRepository();
    }

    /**
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->iatiPolicyMakerRepo->update($activityDetails, $activity);
            $this->logger->info(
                'Policy Maker Updated!',
                ['for' => $activity->policy_maker]
            );
            $this->dbLogger->activity(
                "activity.policy_maker_updated",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Policy Maker could not be updated due to %s', $exception->getMessage()),
                [
                    'policyMaker' => $activityDetails,
                    'trace'       => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPolicyMakerData($id)
    {
        return $this->iatiPolicyMakerRepo->getPolicyMakerData($id);
    }
}
