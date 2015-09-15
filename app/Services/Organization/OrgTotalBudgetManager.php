<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;

class OrgTotalBudgetManager
{

    protected $repo;
    /**
     * @var Guard
     */
    private $auth;
    /**
     * @var Log
     */
    private $log;
    /**
     * @var Version
     */
    private $version;

    /**
     * @param Version $version
     * @param Log     $log
     * @param Guard   $auth
     */
    function __construct(Version $version, Log $log, Guard $auth)
    {
        $this->repo    = $version->getOrganizationElement()->getTotalBudget()->getRepository();
        $this->auth    = $auth;
        $this->log     = $log;
        $this->version = $version;
    }

    /**
     * @param $input
     * @param $organization
     * @return bool
     */
    public function update($input, $organization)
    {
        try {
            $this->repo->update($input, $organization);
            $this->log->info(
                'Organization Total Budget Updated',
                ['for ' => $organization['total_budget']]
            );
            $this->log->activity(
                "organization.total_budget_updated",
                ['name' => $this->auth->user()->organization->name]
            );

            return true;
        } catch (Exception $exception) {

            $this->log->error(
                sprintf('Total Budget could not be updated due to %s', $exception->getMessage()),
                [
                    'OrganizationTotalBudget' => $input,
                    'trace'                   => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    public function getOrganizationData($id)
    {
        return $this->repo->getOrganizationData($id);

    }

    public function getOrganizationTotalBudgetData($id)
    {
        return $this->repo->getOrganizationTotalBudgetData($id);

    }


}