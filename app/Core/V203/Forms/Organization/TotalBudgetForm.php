<?php namespace App\Core\V203\Forms\Organization;

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
            ->addSelect('status', $this->getCodeList('BudgetStatus', 'Activity'), trans('elementForm.status'))
            ->addCollection('period_start', 'Organization\PeriodStart', '', [], trans('elementForm.period_start'))
            ->addCollection('period_end', 'Organization\PeriodEnd', '', [], trans('elementForm.period_end'))
            ->addCollection('value', 'Organization\ValueForm', '', [], trans('elementForm.value'))
            ->addCollection('budget_line', 'Organization\BudgetLineForm', 'budget_line', [], trans('elementForm.budget_line'))
            ->addAddMoreButton('add', 'budget_line')
            ->addRemoveThisButton('remove');
    }
}
