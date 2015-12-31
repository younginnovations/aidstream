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
            ->addSelect('budget_type', $this->getCodeList('BudgetType', 'Activity'), 'Budget Type', $this->addHelpText('Activity_Budget-type'))
            ->addCollection('period_start', 'Activity\PeriodStart')
            ->addCollection('period_end', 'Activity\PeriodEnd')
            ->addCollection('value', 'Activity\ValueForm')
            ->addRemoveThisButton('remove');
    }
}
