<?php namespace App\Core\V201\Forms\Activity\Transactions;

use App\Core\Form\BaseForm;
use App\Core\V201\Traits\Forms\Transaction\Value as ValueCodeLIst;
use Illuminate\Database\DatabaseManager;

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
        $defaultCurrency = getDefaultCurrency();
        $this
            ->add('amount', 'text', ['help_block' => $this->addHelpText('Activity_Transaction_Value-text'), 'required' => true])
            ->add(
                'date',
                'date',
                ['label' => 'Value Date', 'help_block' => $this->addHelpText('Activity_Transaction_Value-value_date'), 'required' => true, 'attr' => ['placeholder' => 'YYYY-MM-DD']]
            );

        !(checkDataExists($this->model['transaction'])) ?: $defaultCurrency = null;
        $this->add(
            'currency',
            'select',
            [
                'choices'       => $this->getCurrencyCodeList(),
                'empty_value'   => 'Select one of the following option :',
                'attr'          => ['class' => 'form-control currency'],
                'default_value' => $defaultCurrency
            ]
        );
    }
}
