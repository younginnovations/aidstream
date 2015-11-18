<?php namespace App\Core\V201\Element\Activity;

use App\Models\Activity\Activity;

/**
 * Class ActivityStatus
 * @package app\Core\V201\Element\Activity
 */
class ActivityStatus
{
    /**
     * @return string
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\ActivityStatus';
    }

    /**
     * @return activity status repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\ActivityStatus');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData = [
            '@attributes' => [
                'code' => $activity['activity_status']
            ]
        ];

        return $activityData;
    }
}
