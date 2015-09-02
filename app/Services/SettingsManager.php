<?php
namespace App\Services;

use App\Core\Version;
use App;

class SettingsManager
{

    protected $repo;
    function __construct(Version $version)
    {
        $this->repo = $version->getSettingsElement()->getRepository();
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