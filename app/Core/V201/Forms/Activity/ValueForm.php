<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;
use Illuminate\Database\DatabaseManager;

/**
 * Class ValueForm
 * @package App\Core\V201\Forms\Activity
 */
class ValueForm extends BaseForm
{
    public function buildForm()
    {
        $defaultCurrency = getDefaultCurrency();

        $this
            ->add('amount', 'text', ['help_block' => $this->addHelpText('Activity_Budget_Value-text'), 'required' => true])
            ->addSelect('currency', $this->getCodeList('Currency', 'Activity'), 'Currency', $this->addHelpText('Activity_Budget_Value-currency'), $defaultCurrency)
            ->add('value_date', 'date', ['help_block' => $this->addHelpText('Activity_Budget_Value-value_date'), 'required' => true]);
    }
}
