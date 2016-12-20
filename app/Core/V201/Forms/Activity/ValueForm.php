<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ValueForm
 * @package App\Core\V201\Forms\Activity
 */
class ValueForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('amount', 'text', ['label' => trans('elementForm.amount'), 'help_block' => $this->addHelpText('Activity_Budget_Value-text'), 'required' => true])
            ->addSelect('currency', $this->getCodeList('Currency', 'Activity'), trans('elementForm.currency'), $this->addHelpText('Activity_Budget_Value-currency'))
            ->add('value_date', 'date', ['label' => trans('elementForm.value_date'), 'help_block' => $this->addHelpText('Activity_Budget_Value-value_date'), 'required' => true]);
    }
}
