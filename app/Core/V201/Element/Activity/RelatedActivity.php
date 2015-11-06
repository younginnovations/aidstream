<?php namespace App\Core\V201\Element\Activity;

/**
 * Class RelatedActivity
 * @package App\Core\V201\Element\Activity
 */
class RelatedActivity
{
    /**
     * @return description form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\RelatedActivities';
    }

    /**
     * @return description repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\RelatedActivity');
    }
}
