<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;

class RecipientOrgBudgetManager
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
        $this->repo    = $version->getOrganizationElement()->getRecipientOrgBudget()->getRepository();
        $this->auth    = $auth;
        $this->log     = $log;
        $this->version = $version;
    }

    public function update($input, $organization)
    {
        try {
            $this->repo->update($input, $organization);
            $this->log->info(
                'Organization Recipient Country Budget  Updated',
                ['for ' => $organization['recipient_organization_budget']]
            );
            $this->log->activity(
                "organization.recipient_organization_budget_updated",
                ['name' => $this->auth->user()->organization->name]
            );

            return true;
        } catch (Exception $exception) {

            $this->log->error(
                sprintf('Recipient Organization Budget could not be updated due to %s', $exception->getMessage()),
                [
                    'OrganizationRecipientOrganizationBudget' => $input,
                    'trace'                                   => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    public function getOrganizationData($id)
    {
        return $this->repo->getOrganizationData($id);

    }

    public function getRecipientOrgBudgetData($id)
    {
        return $this->repo->getRecipientOrgBudgetData($id);

    }

}