<?php namespace App\Core\V202\Forms\Organization;

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
            ->addCollection('period_start', 'Organization\PeriodStart')
            ->addCollection('period_end', 'Organization\PeriodEnd')
            ->addCollection('value', 'Organization\ValueForm')
            ->addCollection('expense_line', 'Organization\ExpenseLine', 'expense_line')
            ->addAddMoreButton('add', 'expense_line')
            ->addRemoveThisButton('remove');
    }
}
