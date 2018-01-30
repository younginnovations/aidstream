<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;
use App\Models\Organization\OrganizationData;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;

class RecipientCountryBudgetManager
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
        $this->repo    = $version->getOrganizationElement()->getRecipientCountryBudget()->getRepository();
        $this->auth    = $auth;
        $this->log     = $log;
        $this->version = $version;
    }

    /**
     * update recipient country budget
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
        } catch (\Exception $exception) {
            $this->log->error($exception, ['OrganizationRecipientCountryBudget' => $input]);
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
     * return recipient country budget
     * @param $id
     * @return model
     */
    public function getRecipientCountryBudgetData($id)
    {
        return $this->repo->getRecipientCountryBudgetData($id);

    }


}