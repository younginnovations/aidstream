<?php namespace App\Core\V202\Forms\Organization;

use App\Core\Form\BaseForm;

/**
 * Class TotalBudgetForm
 * @package App\Core\V202\Forms\Organization
 */
class TotalBudgetForm extends BaseForm
{
    /**
     * build organization total budget form
     */
    public function buildForm()
    {
        $this
            ->addSelect('status', $this->getCodeList('BudgetStatus', 'Activity'))
            ->addCollection('period_start', 'Organization\PeriodStart')
            ->addCollection('period_end', 'Organization\PeriodEnd')
            ->addCollection('value', 'Organization\ValueForm')
            ->addCollection('budget_line', 'Organization\BudgetLineForm', 'budget_line')
            ->addAddMoreButton('add', 'budget_line')
            ->addRemoveThisButton('remove');
    }
}
