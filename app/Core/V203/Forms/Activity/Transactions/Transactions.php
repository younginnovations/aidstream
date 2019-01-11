<?php namespace App\Core\V203\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V203\Traits\Forms\Transaction\Transaction;

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
