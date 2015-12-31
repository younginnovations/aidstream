<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class BudgetItem
 * @package App\Core\V201\Forms\Activity
 */
class BudgetItem extends BaseForm
{
    /**
     * builds the activity budget item form
     */
    public function buildForm()
    {
        $this
            ->addSelect('code', $this->getCodeList('BudgetIdentifier', 'Activity'), 'Code', $this->addHelpText('Activity_CountryBudgetItems_BudgetItem-non_iati'))
            ->addPercentage($this->addHelpText('Activity_CountryBudgetItems_BudgetItem-percentage'))
            ->addCollection('description', 'Activity\BudgetItemDescription', 'description')
            ->addAddMoreButton('add_budget_item_description', 'description')
            ->addRemoveThisButton('remove_budget_item');
    }
}
