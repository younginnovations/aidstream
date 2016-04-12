<?php namespace App\Services\Organization;

use App\Core\Version;
use App\Models\Organization\OrganizationData;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Illuminate\Database\Eloquent\Model;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class RecipientRegionBudgetManager
 * @package App\Services\Organization
 */
class RecipientRegionBudgetManager
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
     * @internal param Log $log
     */
    function __construct(Version $version, Guard $auth, DatabaseManager $database, DbLogger $dbLogger, Logger $logger)
    {
        $this->auth     = $auth;
        $this->database = $database;
        $this->dbLogger = $dbLogger;
        $this->logger   = $logger;
        $this->repo     = $version->getOrganizationElement()->getRecipientRegionBudgetRepository();
    }

    /**
     * update recipient region budget
     * @param array            $input
     * @param OrganizationData $organization
     * @return bool
     */
    public function update(array $input, OrganizationData $organization)
    {
        try {
            $this->database->beginTransaction();
            $this->repo->update($input, $organization);
            $this->database->commit();
            $this->logger->info(
                'Organization Recipient Region Budget Updated',
                ['for' => $organization->recipient_region_budget]
            );
            $this->dbLogger->activity(
                "organization.recipient_region_budget_updated",
                ['name' => $this->auth->user()->organization->name]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                sprintf('Recipient Region Budget could not be updated due to %s', $exception->getMessage()),
                [
                    'OrganizationRecipientRegionBudget' => $input,
                    'trace'                             => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * return organization data
     * @param $id
     * @return Model
     */
    public function getOrganizationData($id)
    {
        return $this->repo->getOrganizationData($id);
    }

    /**
     * return recipient region budget data
     * @param $id
     * @return model
     */
    public function getRecipientRegionBudgetData($id)
    {
        return $this->repo->getRecipientRegionBudgetData($id);
    }
}
