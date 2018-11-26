<?php namespace App\Core\V203\Forms\Activity\Transactions;

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
        $this
            ->add(
                'amount',
                'text',
                ['label' => trans('elementForm.amount'), 'label' => trans('elementForm.amount'), 'help_block' => $this->addHelpText('Activity_Transaction_Value-text'), 'required' => true]
            )
            ->add(
                'date',
                'date',
                ['label' => trans('elementForm.value_date'), 'help_block' => $this->addHelpText('Activity_Transaction_Value-value_date'), 'required' => true, 'attr' => ['placeholder' => 'YYYY-MM-DD']]
            )
            ->add(
                'currency',
                'select',
                [
                    'label'       => trans('elementForm.currency'),
                    'choices'     => $this->getCurrencyCodeList(),
                    'empty_value' => trans('elementForm.select_text'),
                    'attr'        => ['class' => 'form-control currency'],
                ]
            );
    }
}
