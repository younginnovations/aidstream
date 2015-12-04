<?php namespace App\Services\Wizard\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log as Logger;
use Illuminate\Database\DatabaseManager;

/**
 * Class StepTwoManager
 * @package App\Services\Wizard\Activity
 */
class StepTwoManager
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
    protected $stepTwoRepo;
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
        $this->stepTwoRepo = $version->getActivityElement()->getStepTwo()->getRepository();
        $this->auth        = $auth;
        $this->logger      = $logger;
        $this->database    = $database;
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
            $this->stepTwoRepo->update($activityDetails, $activity);
            $this->database->commit();
            $this->logger->info(
                'Step Two Completed!',
                ['for' => [$activity->title, $activity->description]]
            );
            $this->logger->activity(
                "activity.step_two_completed",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                sprintf('Step 2 could not be completes due to %s', $exception->getMessage()),
                [
                    'stepTwo' => $activityDetails,
                    'trace'   => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }
}
