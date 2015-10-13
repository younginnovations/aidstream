<?php namespace App\Core\V201\Wizard\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class StepTwo
 * @package app\Core\V201\Wizard\Repositories\Activity
 */
class StepTwo
{
    protected $activity;

    /**
     * @param Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * update title and description
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->title       = $activityDetails['title'];
        $activity->description = $activityDetails['description'];

        return $activity->save();
    }
}
