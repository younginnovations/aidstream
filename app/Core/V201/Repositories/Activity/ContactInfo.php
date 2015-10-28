<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class ContactInfo
 * @package App\Core\V201\Repositories\Activity
 */
class ContactInfo
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
     * update contact info
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->contact_info = $activityDetails['contact_info'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getContactInfoData($activityId)
    {
        return $this->activity->findorFail($activityId)->contact_info;
    }
}
