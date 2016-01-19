<?php namespace App\Services\Wizard\Activity;

use App\Core\Version;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as Logger;
use Illuminate\Database\DatabaseManager;

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
        $this->activityRepo = $version->getActivityElement()->getWizardRepository();
        $this->auth         = $auth;
        $this->logger       = $logger;
        $this->database     = $database;
    }

    /**
     * save new activity from wizard
     * @param array $identifier
     * @param       $defaultFieldValues
     * @return bool
     */
    public function store(array $identifier, $defaultFieldValues)
    {
        try {
            $this->database->beginTransaction();
            $result = $this->activityRepo->store($identifier, $defaultFieldValues, $this->auth->user()->organization->id);
            $this->activityRepo->saveDefaultValues($result->id, $defaultFieldValues);
            $this->database->commit();
            $this->logger->info(
                'Activity identifier added!',
                ['for' => $identifier['activity_identifier']]
            );
            $this->logger->activity(
                "activity.activity_added",
                [
                    'identifier'      => $identifier['activity_identifier'],
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id,
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                sprintf('Activity identifier couldn\'t be added due to %s', $exception->getMessage()),
                [
                    'ActivityIdentifier' => $identifier,
                    'trace'              => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $activityId
     * @return modal
     */
    public function getActivityData($activityId)
    {
        return $this->activityRepo->getActivityData($activityId);
    }
}
