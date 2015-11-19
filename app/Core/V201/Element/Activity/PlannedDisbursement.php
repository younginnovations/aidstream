<?php namespace App\Core\V201\Element\Activity;

/**
 * Class PlannedDisbursement
 * @package App\Core\V201\Element\Activity
 */
class PlannedDisbursement
{
    /**
     * @return description form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\PlannedDisbursements';
    }

    /**
     * @return description repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\PlannedDisbursement');
    }
}
