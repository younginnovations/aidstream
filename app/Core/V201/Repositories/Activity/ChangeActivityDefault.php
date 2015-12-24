<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

class ChangeActivityDefault
{
    /**
     * @var Settings
     */
    protected $activity;

    /**
     * @param Activity $activity
     */
    function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * update Activity Default
     * @param array    $activityDefaults
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDefaults, Activity $activity)
    {
        $activity->default_field_values = $activityDefaults;

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getActivityDefaultValues($activityId)
    {
        return $this->activity->find($activityId)->default_field_values;
    }
}
