<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\TransactionType;

/**
 * Class Type
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class Type extends BaseForm
{
    use TransactionType;
    protected $showFieldErrors = true;

    /**
     * builds Transaction type form
     */
    public function buildForm()
    {
        $this
            ->addSelect(
                'transaction_type_code',
                $this->getTransactionTypeCodeList(),
                'Transaction Type Code',
                $this->addHelpText('Activity_Transaction_TransactionType-code'),
                ['attr' => ['class' => 'form-control transaction_type']],
                true
            );
    }
}
