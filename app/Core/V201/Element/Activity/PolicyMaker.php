<?php namespace App\Core\V201\Element\Activity;

/**
 * Class PolicyMaker
 * @package App\Core\V201\Element\Activity
 */
class PolicyMaker
{

    /**
     * @return string
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\PolicyMakers';
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\PolicyMaker');
    }
}
