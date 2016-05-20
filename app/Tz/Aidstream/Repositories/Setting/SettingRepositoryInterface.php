<?php namespace App\Tz\Aidstream\Repositories\Setting;


/**
 * Interface SettingRepositoryInterface
 * @package App\Tz\Aidstream\Repositories\Setting
 */
interface SettingRepositoryInterface
{

    /**
     * get settings on basis of organization id
     * @param $orgId
     * @return mixed
     */
    public function findByOrgId($orgId);

    /**
     * create settings
     * @param array $settings
     * @return mixed
     */
    public function create(array $settings);

    /**
     * update settings
     * @param array $settings
     * @param       $id
     * @return mixed
     */
    public function update(array $settings, $id);
}
