<?php namespace App\Core\V203\Forms\Activity\Transactions;

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
                trans('elementForm.transaction_type_code'),
                $this->addHelpText('Activity_Transaction_TransactionType-code'),
                ['attr' => ['class' => 'form-control transaction_type']],
                true
            );
    }
}
