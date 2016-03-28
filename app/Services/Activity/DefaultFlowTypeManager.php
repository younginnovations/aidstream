<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;

/**
 * Class DefaultFlowTypeManager
 * @package App\Services\Activity
 */
class DefaultFlowTypeManager
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

    /**
     * @param Version         $version
     * @param Guard           $auth
     * @param DatabaseManager $database
     * @param DbLogger        $dbLogger
     * @param Logger          $logger
     */
    public function __construct(Version $version, Guard $auth, DatabaseManager $database, DbLogger $dbLogger, Logger $logger)
    {
        $this->auth                = $auth;
        $this->database            = $database;
        $this->defaultFlowTypeRepo = $version->getActivityElement()->getDefaultFlowType()->getRepository();
        $this->dbLogger            = $dbLogger;
        $this->logger              = $logger;
    }

    /**
     * updates Activity Default Flow Type
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->database->beginTransaction();
            $this->defaultFlowTypeRepo->update($activityDetails, $activity);
            $this->database->commit();
            $this->logger->info(
                'Activity Default Flow Type updated!',
                ['for' => $activity->default_flow_type]
            );
            $this->dbLogger->activity(
                "activity.default_flow_type",
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
                sprintf('Activity Default Flow Type could not be updated due to %s', $exception->getMessage()),
                [
                    'defaultFlowType' => $activityDetails,
                    'trace'           => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return Model
     */
    public function getDefaultFlowTypeData($id)
    {
        return $this->defaultFlowTypeRepo->getDefaultFlowTypeData($id);
    }
}
