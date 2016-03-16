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
            ->add('amount', 'text', ['help_block' => $this->addHelpText('Activity_Budget_Value-text'), 'required' => true])
            ->addSelect('currency', $this->getCodeList('Currency', 'Activity'), 'Currency', $this->addHelpText('Activity_Budget_Value-currency'))
            ->add('value_date', 'date', ['help_block' => $this->addHelpText('Activity_Budget_Value-value_date'), 'required' => true]);
    }
}
