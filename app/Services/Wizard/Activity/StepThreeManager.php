<?php namespace App\Services\Wizard\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log as Logger;
use Illuminate\Database\DatabaseManager;

/**
 * Class StepThreeManager
 * @package App\Services\Wizard\Activity
 */
class StepThreeManager
{
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
    protected $stepThreeRepo;
    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @param Version         $version
     * @param Guard           $auth
     * @param DatabaseManager $database
     * @param Logger          $logger
     */
    public function __construct(Version $version, Guard $auth, DatabaseManager $database, Logger $logger)
    {
        $this->stepThreeRepo = $version->getActivityElement()->getStepThree()->getRepository();
        $this->auth          = $auth;
        $this->logger        = $logger;
        $this->database      = $database;
    }

    /**
     * updates title and description of an activity
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->database->beginTransaction();
            $this->stepThreeRepo->update($activityDetails, $activity);
            $this->database->commit();
            $this->logger->info(
                'Step Three Completed!',
                ['for' => [$activity->activity_status, $activity->activity_date]]
            );
            $this->logger->activity(
                "activity.step_three_completed",
                [
                    'activityStatus'  => $activityDetails['activity_status'],
                    'activityDate'    => $activityDetails['activity_date'],
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                sprintf('Step 3 could not be completes due to %s', $exception->getMessage()),
                [
                    'stepThree' => $activityDetails,
                    'trace'     => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }
}
