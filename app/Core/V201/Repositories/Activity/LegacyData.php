<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class LegacyData
 * @package App\Core\V201\Repositories\Activity
 */
class LegacyData
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
     * update activity date
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->legacy_data = $activityDetails['legacy_data'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getLegacyData($activityId)
    {
        return $this->activity->findOrFail($activityId)->legacy_data;
    }
}
