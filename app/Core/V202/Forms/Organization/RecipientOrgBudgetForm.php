<?php namespace App\Core\V202\Forms\Organization;

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
            ->addSelect('status', $this->getCodeList('BudgetStatus', 'Activity'))
            ->addCollection('recipient_organization', 'Organization\RecipientOrgForm')
            ->addCollection('period_start', 'Organization\PeriodStart')
            ->addCollection('period_end', 'Organization\PeriodEnd')
            ->addCollection('value', 'Organization\ValueForm')
            ->addCollection('budget_line', 'Organization\BudgetLineForm', 'budget_line')
            ->addAddMoreButton('add_budget_line', 'budget_line')
            ->addRemoveThisButton('remove_recipient_org_budget');
    }
}
