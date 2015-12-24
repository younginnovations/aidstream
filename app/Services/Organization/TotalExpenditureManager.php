<?php namespace App\Services\Organization;

use App\Core\Version;
use App\Models\Organization\OrganizationData;
use Illuminate\Auth\Guard;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class TotalExpenditureManager
 * @package App\Services\Organization
 */
class TotalExpenditureManager
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
        $this->auth                 = $auth;
        $this->database             = $database;
        $this->dbLogger             = $dbLogger;
        $this->logger               = $logger;
        $this->totalExpenditureRepo = $version->getOrganizationElement()->getTotalExpenditureRepository();
    }

    /**
     * get organization total expenditure data
     * @param $orgId
     * @return mixed
     */
    public function getOrganizationTotalExpenditureData($orgId)
    {
        return $this->totalExpenditureRepo->getOrganizationTotalExpenditureData($orgId);
    }

    /**
     * get organization total expenditure data
     * @param $orgId
     * @return mixed
     */
    public function getOrganizationData($orgId)
    {
        return $this->totalExpenditureRepo->getOrganizationData($orgId);
    }

    /**
     * update organization total expenditure
     * @param array            $totalExpenditure
     * @param OrganizationData $organizationData
     * @return bool
     */
    public function update(array $totalExpenditure, OrganizationData $organizationData)
    {
        try {
            $this->totalExpenditureRepo->update($totalExpenditure, $organizationData);
            $this->logger->info(
                'Organization Total Expenditure Updated',
                ['for' => $organizationData->total_expenditure]
            );
            $this->dbLogger->activity(
                "organization.total_expenditure_updated",
                ['name' => $this->auth->user()->organization->name]
            );

            return true;
        } catch (Exception $exception) {

            $this->log->error(
                sprintf('Total Expenditure could not be updated due to %s', $exception->getMessage()),
                [
                    'OrganizationTotalBudget' => $totalExpenditure,
                    'trace'                   => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }
}
