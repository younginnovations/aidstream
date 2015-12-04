<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class Title
 * contains the function that returns the title form and title repository
 * @package app\Core\V201\Element\Activity
 */
class Title extends BaseElement
{
    /**
     * @return title form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\Title';
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\Title');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $titles         = (array) $activity->title;
        $activityData[] = [
            'narrative' => $this->buildNarrative($titles)
        ];

        return $activityData;
    }
}
