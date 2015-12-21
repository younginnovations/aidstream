<?php namespace App\Core\V202\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Budget
 * @package App\Core\V202\Forms\Activity
 */
class Budget extends BaseForm
{
    /**
     * builds activity budget form
     */
    public function buildForm()
    {
        $this
            ->addSelect('budget_type', $this->getCodeList('BudgetType', 'Activity'))
            ->addSelect('status', $this->getCodeList('BudgetStatus', 'Activity'))
            ->addCollection('period_start', 'Activity\PeriodStart')
            ->addCollection('period_end', 'Activity\PeriodEnd')
            ->addCollection('value', 'Activity\ValueForm')
            ->addRemoveThisButton('remove');
    }
}
