<?php namespace App\Core\V203\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class HumanitarianScope
 * @package App\Core\V202\Repositories\Organization
 */
class HumanitarianScope
{
    /**
     * @var Activity
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
     * @param array    $input
     * @param Activity $activity
     * @return bool
     */
    public function update(array $input, Activity $activity)
    {
        $activity->humanitarian_scope = $input['humanitarian_scope'];

        return $activity->save();
    }

    /**
     * get activity data
     * @param $activityId
     * @return model
     */
    public function getActivityData($activityId)
    {
        return $this->activity->find($activityId);
    }

    /**
     * get humanitarian scope organization data
     * @param $activityId
     * @return model
     */
    public function getActivityHumanitarianScopeData($activityId)
    {
        return $this->activity->find($activityId)->humanitarian_scope;
    }
}
