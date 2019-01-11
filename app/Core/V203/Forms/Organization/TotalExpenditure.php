<?php namespace App\Core\V203\Forms\Organization;

use App\Core\Form\BaseForm;

/**
 * Class TotalExpenditure
 * @package App\Core\V202\Forms\Organization
 */
class TotalExpenditure extends BaseForm
{
    /**
     * build organization Total Expenditure form
     */
    public function buildForm()
    {
        $this
            ->addCollection('period_start', 'Organization\PeriodStart', '', [], trans('elementForm.period_start'))
            ->addCollection('period_end', 'Organization\PeriodEnd', '', [], trans('elementForm.period_end'))
            ->addCollection('value', 'Organization\ValueForm', '', [], trans('elementForm.value'))
            ->addCollection('expense_line', 'Organization\ExpenseLine', 'expense_line', [], trans('elementForm.expense_line'))
            ->addAddMoreButton('add', 'expense_line')
            ->addRemoveThisButton('remove');
    }
}
