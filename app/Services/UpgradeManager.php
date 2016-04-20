<?php namespace App\Services;

use App\Core\Version;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class UpgradeManager
 * @package App\Services
 */
class UpgradeManager
{
    /**
     * @var
     */
    protected $repo;
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
     * @var Guard
     */
    protected $auth;

    /**
     * @param Version         $version
     * @param Guard           $auth
     * @param DatabaseManager $database
     * @param DbLogger        $dbLogger
     * @param Logger          $logger
     */
    function __construct(Version $version, Guard $auth, DatabaseManager $database, DbLogger $dbLogger, Logger $logger)
    {
        $this->repo     = $version->getSettingsElement()->getUpgradeRepository();
        $this->database = $database;
        $this->dbLogger = $dbLogger;
        $this->logger   = $logger;
        $this->auth     = $auth;
    }

    /**
     * upgrade data version wise
     * @param $orgId
     * @param $version
     * @return bool
     */
    public function upgrade($orgId, $version)
    {
        try {
            $this->database->beginTransaction();
            $this->repo->upgrade($orgId, $version);
            $this->database->commit();
            $this->logger->info(
                sprintf('Version Upgraded to %s for Organization %s!', $version, $this->auth->user()->organization->name),
                ['for' => $orgId]
            );
            $this->dbLogger->activity(
                "activity.version_upgraded",
                [
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id,
                    'version'         => $version
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error($exception, ['version' => $version]);
        }

        return false;
    }
}
