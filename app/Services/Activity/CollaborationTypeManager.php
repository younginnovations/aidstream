<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;

/**
 * Class CollaborationTypeManager
 * @package App\Services\Activity
 */
class CollaborationTypeManager
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
        $this->auth                  = $auth;
        $this->database              = $database;
        $this->collaborationTypeRepo = $version->getActivityElement()->getCollaborationType()->getRepository();
        $this->dbLogger              = $dbLogger;
        $this->logger                = $logger;
    }

    /**
     * updates Activity Collaboration Type
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->database->beginTransaction();
            $this->collaborationTypeRepo->update($activityDetails, $activity);
            $this->database->commit();
            $this->logger->info(
                'Activity Collaboration Type updated!',
                ['for' => $activity->collaboration_type]
            );
            $this->dbLogger->activity(
                "activity.collaboration_type",
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
                sprintf('Activity Collaboration Type could not be updated due to %s', $exception->getMessage()),
                [
                    'collaborationType' => $activityDetails,
                    'trace'             => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return model
     */
    public function getCollaborationTypeData($id)
    {
        return $this->collaborationTypeRepo->getCollaborationTypeData($id);
    }
}
