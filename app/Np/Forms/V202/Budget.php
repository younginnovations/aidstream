<?php namespace App\Np\Forms\V202;


use App\Np\Forms\NpBaseForm;

/**
 * Class BudgetForm
 * @package App\Np\Forms\V202
 */
class Budget extends NpBaseForm
{
    /**
     * Form structure for funding organisation and implementing organisation.
     */
    public function buildForm()
    {
        $required = true;

        $currency = $this->getCodeList('Currency', 'Activity');

        $this->add('startDate', 'date', ['label' => trans('lite/elementForm.period_start'), 'required' => $required, 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->add('endDate', 'date', ['label' => trans('lite/elementForm.period_end'), 'required' => $required, 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->add('amount', 'text', ['label' => trans('lite/elementForm.amount'), 'required' => $required, 'wrapper' => ['class' => 'form-group col-sm-6']])
             ->addSelect(
                 'currency',
                 $currency,
                 trans('lite/elementForm.currency'),
                 null,
                 null,
                 false,
                 [
                     'wrapper' => ['class' => 'form-group col-sm-6']
                 ]
             )
             ->addButton('remove_button', trans('lite/elementForm.remove'), 'budget', 'remove_from_collection');
    }
}
