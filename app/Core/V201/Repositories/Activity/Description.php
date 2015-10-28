<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class Description
 * @package App\Core\V201\Repositories\Activity
 */
class Description
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
     * update other Identifier
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->description = $activityDetails['description'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getDescriptionData($activityId)
    {
        return $this->activity->findorFail($activityId)->description;
    }
}
