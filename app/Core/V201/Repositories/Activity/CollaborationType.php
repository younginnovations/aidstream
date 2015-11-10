<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class CollaborationType
 * @package App\Core\V201\Repositories\Activity
 */
class CollaborationType
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
     * update Collaboration Type
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->collaboration_type = $activityDetails['collaboration_type'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getCollaborationTypeData($activityId)
    {
        return $this->activity->findOrFail($activityId)->collaboration_type;
    }
}
