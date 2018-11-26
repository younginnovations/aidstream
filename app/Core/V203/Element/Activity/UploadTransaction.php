<?php namespace App\Core\V203\Element\Activity;

use App\Core\V201\Element\Activity\UploadTransaction as UploadTransactionV201;

/**
 * Class UploadTransaction
 * @package App\Core\V202\Element\Activity
 */
class UploadTransaction extends UploadTransactionV201
{

    /**
     * @return transaction repository
     */
    public function getRepository()
    {
        return App('App\Core\V202\Repositories\Activity\UploadTransaction');
    }
}
