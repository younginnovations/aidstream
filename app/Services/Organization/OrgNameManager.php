<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;

class OrgNameManager
{

    protected $repo;
    function __construct(Version $version)
    {
        $this->repo = $version->getOrganizationElement()->getName()->getRepository();
    }

    public function getOrganizationNameData($id)
    {
        return $this->repo->getOrganizationNameData($id);

    }
    /**
     * @param $organization
     * @param $input
     */
    public function create($organization, $input)
    {
        $this->repo->create($organization, $input);
    }

    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organization)
    {
        $this->repo->update($input, $organization);
    }


}