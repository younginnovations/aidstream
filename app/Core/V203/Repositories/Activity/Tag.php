<?php 
namespace App\Core\V203\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class Sector
 * @package App\Core\V201\Repositories\Activity
 */
class Tag
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
     * update activity sector
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->tag = $activityDetails['tag'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getTagData($activityId)
    {
        return $this->activity->findOrFail($activityId)->tag;
    }
}
