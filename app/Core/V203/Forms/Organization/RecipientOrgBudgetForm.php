<?php namespace App\Core\V203\Forms\Organization;

use App\Core\Form\BaseForm;

/**
 * Class RecipientOrgBudgetForm
 * @package App\Core\V202\Forms\Organization
 */
class RecipientOrgBudgetForm extends BaseForm
{
    /**
     * build recipient organization budget form
     */
    public function buildForm()
    {
        $this
            ->addSelect('status', $this->getCodeList('BudgetStatus', 'Activity'), trans('elementForm.status'))
            ->addCollection('recipient_organization', 'Organization\RecipientOrgForm', '', [], trans('elementForm.recipient_organisation'))
            ->addCollection('period_start', 'Organization\PeriodStart', '', [], trans('elementForm.period_start'))
            ->addCollection('period_end', 'Organization\PeriodEnd', '', [], trans('elementForm.period_end'))
            ->addCollection('value', 'Organization\ValueForm', '', [], trans('elementForm.value'))
            ->addCollection('budget_line', 'Organization\BudgetLineForm', 'budget_line', [], trans('elementForm.budget_line'))
            ->addAddMoreButton('add_budget_line', 'budget_line')
            ->addRemoveThisButton('remove_recipient_org_budget');
    }
}
