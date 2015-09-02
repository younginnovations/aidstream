<?php
namespace App\Core\V201\Repositories;

use App\Core\Repositories\SettingsRepositoryInterface;
use App\Models\Settings;

class SettingsRepository implements SettingsRepositoryInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function getSettings($id)
    {
//        return Organization::findorFail($id);
    }

    /**
     * @param $input
     * @param $id
     */
    public function updateSettings($input, $id)
    {
        // update settings
    }

}