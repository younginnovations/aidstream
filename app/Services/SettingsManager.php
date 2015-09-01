<?php
namespace App\Services;

use App\Core\Version;
use App;

class OrganizationManager
{

    protected $repo;
    function __construct(Version $version)
    {
        $this->repo = $version->getSettigsElement()->getRepository();
    }

    public function getSettings($id)
    {
        return $this->repo->getSettings($id);

    }

    public function updateSettings($input, $id)
    {
        $this->repo->updateSettings($input, $id);
    }



}