<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

/**
 * Class RecipientOrgBudgetForm
 * @package App\Core\V201\Forms\Organization
 */
class RecipientOrgBudgetForm extends BaseForm
{
    /**
     * build recipient organization budget form
     */
    public function buildForm()
    {
        $this
            ->addCollection('recipient_organization', 'Organization\RecipientOrgForm')
            ->addCollection('period_start', 'Organization\PeriodStart')
            ->addCollection('period_end', 'Organization\PeriodEnd')
            ->addCollection('value', 'Organization\ValueForm')
            ->addCollection('budget_line', 'Organization\BudgetLineForm', 'budget_line')
            ->addAddMoreButton('add_budget_line', 'budget_line')
            ->addRemoveThisButton('remove_recipient_org_budget');
    }
}
