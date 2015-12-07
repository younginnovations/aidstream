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
            ->add(
                'code',
                'select',
                [
                    'choices'     => $this->getCodeList('BudgetIdentifier', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                ]
            )
            ->addPercentage()
            ->addCollection('description', 'Activity\BudgetItemDescription', 'description')
            ->addAddMoreButton('add_budget_item_description', 'description')
            ->addRemoveThisButton('remove_budget_item');
    }
}
