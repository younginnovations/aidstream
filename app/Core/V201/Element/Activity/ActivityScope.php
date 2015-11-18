<?php namespace App\Core\V201\Element\Activity;

use App\Models\Activity\Activity;

/**
 * Class ActivityScope
 * @package app\Core\V201\Element\Activity
 */
class ActivityScope
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\ActivityScope";
    }

    /**
     * @return activity scope repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\ActivityScope');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData = [
            '@attributes' => [
                'code' => $activity['activity_scope']
            ]
        ];

        return $activityData;
    }
}
