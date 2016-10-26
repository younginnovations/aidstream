<?php namespace App\Core\V202\Forms\Organization;

use App\Core\Form\BaseForm;

/**
 * Class RecipientRegionBudget
 * @package App\Core\V202\Forms\Organization
 */
class RecipientRegionBudget extends BaseForm
{
    /**
     * build recipient region budget form
     */
    public function buildForm()
    {
        $this
            ->addSelect('status', $this->getCodeList('BudgetStatus', 'Activity'))
            ->addCollection('recipient_region', 'Organization\RecipientRegion', 'recipient_region', [], 'Recipient Organisation')
            ->addCollection('period_start', 'Organization\PeriodStart')
            ->addCollection('period_end', 'Organization\PeriodEnd')
            ->addCollection('value', 'Organization\ValueForm')
            ->addCollection('budget_line', 'Organization\BudgetLineForm', 'budget_line')
            ->addAddMoreButton('add_budget_line', 'budget_line')
            ->addRemoveThisButton('remove_recipient_region_budget');
    }
}
