<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class RecipientRegion
 * @package App\Core\V201\Repositories\Activity
 */
class RecipientRegion
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
     * update recipient region
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->recipient_region = $activityDetails['recipient_region'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getRecipientRegionData($activityId)
    {
        return $this->activity->findOrFail($activityId)->recipient_region;
    }
}
