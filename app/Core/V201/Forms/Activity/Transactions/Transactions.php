<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\Transaction;

/**
 * Class Transactions
 * @package App\Core\V201\Forms\Activity
 */
class Transactions extends BaseForm
{
    use Transaction;
    /**
     * builds activity Transaction form
     */
    public function buildForm()
    {
        $this
            ->addTransaction();
    }
}
