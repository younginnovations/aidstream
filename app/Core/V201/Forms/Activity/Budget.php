<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Budget
 * @package App\Core\V201\Forms\Activity
 */
class Budget extends BaseForm
{
    /**
     * builds activity budget form
     */
    public function buildForm()
    {
        $this
            ->addSelect('budget_type', $this->getCodeList('BudgetType', 'Activity'), trans('elementForm.budget_type'), $this->addHelpText('Activity_Budget-type'))
            ->addCollection('period_start', 'Activity\PeriodStart', '', [], trans('elementForm.period_start'))
            ->addCollection('period_end', 'Activity\PeriodEnd', '', [], trans('elementForm.period_end'))
            ->addCollection('value', 'Activity\ValueForm', '', [], trans('elementForm.value'))
            ->addRemoveThisButton('remove');
    }
}
