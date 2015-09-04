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

    public function storeSettings($input, $organization)
    {
        $this->repo->storeSettings($input, $organization);
    }

    public function updateSettings($input, $organization, $settings)
    {
        $this->repo->updateSettings($input, $organization, $settings);
    }



}