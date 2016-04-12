<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;
use App\Models\Organization\OrganizationData;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * @param array            $input
     * @param OrganizationData $organization
     * @return bool
     */
    public function update(array $input, OrganizationData $organization)
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
     * @return model
     */
    public function getRecipientOrgBudgetData($id)
    {
        return $this->repo->getRecipientOrgBudgetData($id);

    }

}