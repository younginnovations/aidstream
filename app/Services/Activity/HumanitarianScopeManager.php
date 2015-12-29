<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class HumanitarianScopeManager
 * @package App\Services\Activity
 */
class HumanitarianScopeManager
{
    /**
     * @var Guard
     */
    protected $auth;
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
     * @var Version
     */
    protected $version;

    /**
     * @param Version         $version
     * @param Guard           $auth
     * @param DatabaseManager $database
     * @param DbLogger        $dbLogger
     * @param Logger          $logger
     */
    function __construct(Version $version, Guard $auth, DatabaseManager $database, DbLogger $dbLogger, LOgger $logger)
    {
        $this->auth                  = $auth;
        $this->database              = $database;
        $this->dbLogger              = $dbLogger;
        $this->logger                = $logger;
        $this->humanitarianScopeRepo = $version->getActivityElement()->getHumanitarianScopeRepository();
    }

    /**
     * get activity data
     * @param $activityId
     * @return mixed
     */
    public function getActivityData($activityId)
    {
        return $this->humanitarianScopeRepo->getActivityData($activityId);
    }

    /**
     * get activity Humanitarian Scope data
     * @param $activityId
     * @return mixed
     */
    public function getActivityHumanitarianScopeData($activityId)
    {
        return $this->humanitarianScopeRepo->getActivityHumanitarianScopeData($activityId);
    }

    /**
     * update organization total expenditure
     * @param array    $humanitarianScope
     * @param Activity $activity
     * @return bool
     */
    public function update(array $humanitarianScope, Activity $activity)
    {
        try {
            $this->database->beginTransaction();
            $this->humanitarianScopeRepo->update($humanitarianScope, $activity);
            $this->database->commit();
            $this->logger->info('Activity Humanitarian Scope Updated', ['for' => $activity->humanitarian_scope]);
            $this->dbLogger->activity(
                "activity.humanitarian_scope_updated",
                ['activity_id' => $activity->id, 'organization' => $this->auth->user()->organization->name, 'organization_id' => $this->auth->user()->organization->id]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                sprintf('Humanitarian Scope could not be updated due to %s', $exception->getMessage()),
                [
                    'HumanitarianScope' => $humanitarianScope,
                    'trace'             => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }
}
