<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;

class RecipientCountryBudgetManager
{

    protected $repo;
    function __construct(Version $version)
    {
        $this->repo = $version->getOrganizationElement()->getRecipientCountryBudget()->getRepository();
    }

    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organization)
    {
        $this->repo->update($input, $organization);
    }

    public function getOrganizationData($id)
    {
        return $this->repo->getOrganizationData($id);

    }

    public function getRecipientCountryBudgetData($id)
    {
        return $this->repo->getRecipientCountryBudgetData($id);

    }


}