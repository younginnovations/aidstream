<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;
use App\Models\Organization\OrganizationData;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;

class RecipientCountryBudgetManager
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
        $this->repo    = $version->getOrganizationElement()->getRecipientCountryBudget()->getRepository();
        $this->auth    = $auth;
        $this->log     = $log;
        $this->version = $version;
    }

    /**
     * write brief description
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
                ['for ' => $organization['recipient_country_budget']]
            );
            $this->log->activity(
                "organization.recipient_country_updated",
                ['name' => $this->auth->user()->organization->name]
            );

            return true;
        } catch (Exception $exception) {

            $this->log->error(
                sprintf('Recipient Country Budget could not be updated due to %s', $exception->getMessage()),
                [
                    'OrganizationRecipientCountryBudget' => $input,
                    'trace'                              => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * write brief description
     * @param $id
     * @return model
     */
    public function getOrganizationData($id)
    {
        return $this->repo->getOrganizationData($id);

    }

    /**
     * write brief description
     * @param $id
     * @return model
     */
    public function getRecipientCountryBudgetData($id)
    {
        return $this->repo->getRecipientCountryBudgetData($id);

    }


}