<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Settings;

class ChangeActivityDefault
{
    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @param Settings $settings
     */
    function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * update Activity Default
     * @param array $activityDefaults
     * @return bool
     */
    public function update(array $activityDefaults, Settings $settings)
    {
        $settings->default_field_values = $activityDefaults;

        return $settings->save();
    }

    /**
     * @param $orgId
     * @return array
     */
    public function getActivityDefaultValues($orgId)
    {
        return $this->settings->where('organization_id', $orgId)->get()->default_field_values;
    }
}
