<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log as Logger;

/**
 * Class ActivityManager
 * @package App\Services\Activity
 */
class ActivityManager
{
    protected $activityRepo;
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Log
     */
    protected $logger;
    /**
     * @var Version
     */
    protected $version;

    /**
     * @param Version $version
     * @param Logger  $logger
     * @param Guard   $auth
     */
    public function __construct(Version $version, Guard $auth, Logger $logger)
    {
        $this->auth            = $auth;
        $this->logger          = $logger;
        $this->version         = $version;
        $this->activityElement = $version->getActivityElement();
        $this->activityRepo    = $this->activityElement->getRepository();
        $this->transactionRepo = $this->activityElement->getTransactionRepository();
        $this->resultRepo      = $this->activityElement->getResultRepository();
    }

    /**
     * insert activity identifier
     * @param array $input
     * @param       $organizationId
     * @return bool
     */
    public function store(array $input, $organizationId)
    {
        try {
            $result = $this->activityRepo->store($input, $organizationId);
            $this->logger->info(
                'Activity identifier added',
                ['for ' => $input['activity_identifier']]
            );
            $this->logger->activity(
                "activity.added",
                [
                    'identifier'      => $input['activity_identifier'],
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id,
                ]
            );

            return $result;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Activity identifier couldn\'t be added due to %s', $exception->getMessage()),
                [
                    'ActivityIdentifier' => $input,
                    'trace'              => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $organizationId
     * @return modal
     */
    public function getActivities($organizationId)
    {
        return $this->activityRepo->getActivities($organizationId);
    }

    /**
     * @param $activityId
     * @return modal
     */
    public function getActivityData($activityId)
    {
        return $this->activityRepo->getActivityData($activityId);
    }

    /**
     * @param array    $input
     * @param Activity $activityData
     */
    public function updateStatus(array $input, Activity $activityData)
    {
        return $this->activityRepo->updateStatus($input, $activityData);
    }

    /**
     * @param $activity_id
     * @return mixed
     */
    public function resetActivityWorkflow($activity_id)
    {
        return $this->activityRepo->resetActivityWorkflow($activity_id);
    }

    /**
     * @return mixed
     */
    public function getActivityElement()
    {
        return $this->activityElement;
    }

    /**
     * @param $activityId
     * @return mixed
     */
    public function getTransactionData($activityId)
    {
        return $this->transactionRepo->getTransactionData($activityId);
    }

    /**
     * @param $activityId
     * @return mixed
     */
    public function getResultData($activityId)
    {
        return $this->resultRepo->getResults($activityId);
    }
}
