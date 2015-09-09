<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;

class RecipientOrgBudgetManager
{

    protected $repo;
    function __construct(Version $version)
    {
        $this->repo = $version->getOrganizationElement()->getRecipientOrgBudget()->getRepository();
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

}