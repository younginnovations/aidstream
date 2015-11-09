<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;

/**
 * Class CapitalSpendManager
 * @package App\Services\Activity
 */
class CapitalSpendManager
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
        $this->auth             = $auth;
        $this->dbLogger         = $dbLogger;
        $this->database         = $database;
        $this->capitalSpendRepo = $version->getActivityElement()->getCapitalSpend()->getRepository();
        $this->logger           = $logger;
    }

    /**
     * updates Activity Capital Spend
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->database->beginTransaction();
            $this->capitalSpendRepo->update($activityDetails, $activity);
            $this->database->commit();
            $this->logger->info(
                'Activity Capital Spend updated!',
                ['for' => $activity->capital_spend]
            );
            $this->dbLogger->activity(
                "activity.capital_spend",
                [
                    'capital_spend'   => $activityDetails['capital_spend'],
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                sprintf('Activity Capital Spend could not be updated due to %s', $exception->getMessage()),
                [
                    'capitalSpend' => $activityDetails,
                    'trace'        => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return model
     */
    public function getCapitalSpendData($id)
    {
        return $this->capitalSpendRepo->getCapitalSpendData($id);
    }
}
