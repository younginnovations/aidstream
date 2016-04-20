<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;
use App\Models\Organization\OrganizationData;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrgTotalBudgetManager
 * @package App\Services\Organization
 */
class OrgTotalBudgetManager
{

    protected $repo;
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Log
     */
    protected $log;
    /**
     * @var Version
     */
    protected $version;

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
    public function update(array $input, OrganizationData $organization)
    {
        try {
            $this->repo->update($input, $organization);
            $this->log->info('Organization Total Budget Updated', ['for ' => $organization['total_budget']]);
            $this->log->activity("organization.total_budget_updated", ['name' => $this->auth->user()->organization->name]);

            return true;
        } catch (Exception $exception) {
            $this->log->error($exception, ['OrganizationTotalBudget' => $input]);
        }

        return false;
    }

    /**
     * @param $id
     * @return Model
     */
    public function getOrganizationData($id)
    {
        return $this->repo->getOrganizationData($id);
    }

    /**
     * @param $id
     * @return Model
     */
    public function getOrganizationTotalBudgetData($id)
    {
        return $this->repo->getOrganizationTotalBudgetData($id);
    }
}
