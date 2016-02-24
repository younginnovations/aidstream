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
                'code_text',
                'text',
                [
                    'label'      => 'Code',
                    'wrapper'    => ['class' => 'form-group code_text codes'],
                    'help_block' => $this->addHelpText('Activity_CountryBudgetItems_BudgetItem-non_iati')
                ]
            )
            ->add(
                'code',
                'select',
                [
                    'choices'     => $this->getCodeList('BudgetIdentifier', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'wrapper'     => ['class' => 'form-group code codes hidden'],
                    'help_block'  => $this->addHelpText('Activity_CountryBudgetItems_BudgetItem-non_iati')
                ]
            )
            ->addPercentage($this->addHelpText('Activity_CountryBudgetItems_BudgetItem-percentage'))
            ->addCollection('description', 'Activity\BudgetItemDescription')
            ->addRemoveThisButton('remove_budget_item');
    }
}
