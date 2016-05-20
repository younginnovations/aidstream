<?php namespace App\Tz\Aidstream\Repositories\Setting;

use App\Tz\Aidstream\Models\Settings;

/**
 * Class SettingRepository
 * @package App\Tz\Aidstream\Repositories\Setting
 */
class SettingRepository implements SettingRepositoryInterface
{

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * SettingRepository constructor.
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Get settings model on basis of organization
     * @param $orgId
     * @return mixed
     */
    public function findByOrgId($orgId)
    {
        return $this->settings->where('organization_id', '=', $orgId)->first();
    }

    /**
     * Create new settings
     * @param array $settings
     * @return bool
     */
    public function create(array $settings)
    {
        $setting = $this->settings->newInstance($settings);

        return $setting->save();
    }

    /**
     * Update Settings
     * @param array $settings
     * @param       $id
     * @return mixed
     */
    public function update(array $settings, $id)
    {
        $setting = $this->settings->find($id);

        return $setting->update($settings);
    }
}
