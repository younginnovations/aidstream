<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class ActivityDate
 * @package app\Core\V201\Element\Activity
 */
class ActivityDate extends BaseElement
{
    /**
     * @return  Activity Date form
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleActivityDate";
    }

    /**
     * @return Activity Date repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\ActivityDate');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData = [];
        $activityDate = (array) $activity->activity_date;
        foreach ($activityDate as $ActivityDate) {
            $activityData[] = [
                '@attributes' => [
                    'type'     => $ActivityDate['type'],
                    'iso-date' => $ActivityDate['date']
                ],
                'narrative'   => $this->buildNarrative($ActivityDate['narrative'])
            ];
        }

        return $activityData;
    }
}
