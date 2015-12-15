<?php namespace App\Core\V201\Element\Activity;

/**
 * Class UploadActivity
 * @package App\Core\V201\Element\Activity
 */
class UploadActivity
{
    /**
     * @return transaction form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\UploadActivity';
    }

    /**
     * @return transaction repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\UploadActivity');
    }
}
