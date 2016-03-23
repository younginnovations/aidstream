<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Illuminate\Database\Eloquent\Model;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;

/**
 * Class ChangeActivityDefaultManager
 * @package App\Services\Activity
 */
class ChangeActivityDefaultManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Version
     */
    protected $version;
    /**
     * @var DatabaseManager
     */
    protected $database;
    /**
     * @var DbLogger
     */
    protected $dbLogger;
    /**
     * @var Logger
     */
    protected $logger;
    protected $changeActivityDefaultRepo;

    /**
     * @param Version         $version
     * @param Guard           $auth
     * @param DatabaseManager $database
     * @param DbLogger        $dbLogger
     * @param Logger          $logger
     */
    public function __construct(Version $version, Guard $auth, DatabaseManager $database, DbLogger $dbLogger, Logger $logger)
    {
        $this->auth                      = $auth;
        $this->dbLogger                  = $dbLogger;
        $this->database                  = $database;
        $this->logger                    = $logger;
        $this->changeActivityDefaultRepo = $version->getActivityElement()->getChangeActivityDefault()->getRepository();
    }

    /**
     * updates Activity Default values
     * @param array    $activityDefaults
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDefaults, Activity $activity)
    {
        try {
            $this->database->beginTransaction();
            $this->changeActivityDefaultRepo->update($activityDefaults, $activity);
            $this->database->commit();
            $this->logger->info(
                'Activity Default Values updated!',
                ['for' => $activityDefaults]
            );
            $this->dbLogger->activity(
                "activity.activity_default_values",
                [
                    'organization_id' => $this->auth->user()->organization->id,
                    'activity_id'     => $activity->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                sprintf('Activity Default Values could not be updated due to %s', $exception->getMessage()),
                [
                    'activityDefaultValues' => $activityDefaults,
                    'trace'                 => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return Model
     */
    public function getActivityDefaultValues($id)
    {
        return $this->changeActivityDefaultRepo->getActivityDefaultValues($id);
    }
}
