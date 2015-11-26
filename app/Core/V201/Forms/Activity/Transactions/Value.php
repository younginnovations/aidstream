<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\Value as ValueCodeLIst;

/**
 * Class Value
 * @package App\Core\V201\Forms\Activity\Transactions
 */
class Value extends BaseForm
{
    use ValueCodeLIst;
    protected $showFieldErrors = true;

    /**
     * builds Transaction value form
     */
    public function buildForm()
    {
        $this
            ->add('amount', 'text')
            ->add('date', 'date', ['label' => 'Value Date'])
            ->add('currency', 'select', ['choices' => $this->getCurrencyCodeList(), 'attr' => ['class' => 'form-control currency']]);
    }
}
