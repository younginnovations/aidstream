<?php namespace App\Core\V202\Forms\Organization;

use App\Core\Form\BaseForm;

/**
 * Class RecipientRegionBudgets
 * @package App\Core\V202\Forms\Organization
 */
class RecipientRegionBudgets extends BaseForm
{
    /**
     * build recipient region budget form
     */
    public function buildForm()
    {
        $this
            ->addCollection('recipient_region_budget', 'Organization\RecipientRegionBudget', 'recipient_region_budget')
            ->addAddMoreButton('add_recipient_region_budget', 'recipient_region_budget');
    }
}
