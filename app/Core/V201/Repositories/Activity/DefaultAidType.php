<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class DefaultAidType
 * @package App\Core\V201\Repositories\Activity
 */
class DefaultAidType
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
     * update Default Aid Type
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->default_aid_type = $activityDetails['default_aid_type'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getDefaultAidTypeData($activityId)
    {
        return $this->activity->findOrFail($activityId)->default_aid_type;
    }
}
