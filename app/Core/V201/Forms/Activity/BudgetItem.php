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
                    'choices' => $this->getCodeList('BudgetIdentifier', 'Activity'),
                ]
            )
            ->getPercentage()
            ->add(
                'description',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\BudgetItemDescription',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form description'
                    ]
                ]
            )
            ->addAddMoreButton('add_budget_item_description', 'description')
            ->addRemoveThisButton('remove_budget_item');
    }
}
