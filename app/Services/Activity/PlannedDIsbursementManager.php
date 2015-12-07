<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Auth\Guard;
use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Logging\Log;

/**
 * Class PlannedDisbursementManager
 * @package App\Services\Activity
 */
class PlannedDisbursementManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Log
     */
    protected $dbLogger;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Version
     */
    protected $version;

    /**
     * @param Version         $version
     * @param Log             $dbLogger
     * @param Guard           $auth
     * @param LoggerInterface $logger
     */
    public function __construct(Version $version, Log $dbLogger, Guard $auth, LoggerInterface $logger)
    {
        $this->auth                 = $auth;
        $this->dbLogger             = $dbLogger;
        $this->logger               = $logger;
        $this->iatiPlannedDisbursementRepo = $version->getActivityElement()->getPlannedDisbursement()->getRepository();
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
            $this->iatiPlannedDisbursementRepo->update($activityDetails, $activity);
            $this->logger->info(
                'Planned Disbursement Updated!',
                ['for' => $activity->planned_disbursement]
            );
            $this->dbLogger->activity(
                "activity.planned_disbursement_updated",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Planned Disbursement could not be updated due to %s', $exception->getMessage()),
                [
                    'Planned Disbursement' => $activityDetails,
                    'trace'                => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPlannedDisbursementData($id)
    {
        return $this->iatiPlannedDisbursementRepo->getPlannedDisbursementData($id);
    }
}
