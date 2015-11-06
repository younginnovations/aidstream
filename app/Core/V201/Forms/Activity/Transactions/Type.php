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
            ->add(
                'transaction_type_code',
                'select',
                [
                    'choices' => $this->getTransactionTypeCodeList(),
                    'attr'    => ['class' => 'form-control transaction_type']
                ]
            );
    }
}
