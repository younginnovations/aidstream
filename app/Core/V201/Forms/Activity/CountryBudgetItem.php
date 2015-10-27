<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class CountryBudgetItem
 * @package App\Core\V201\Forms\Activity
 */
class CountryBudgetItem extends BaseForm
{
    /**
     * builds the activity country budget item form
     */
    public function buildForm()
    {
        $this
            ->add(
                'vocabulary',
                'select',
                [
                    'choices' => $this->getCodeList('BudgetIdentifierVocabulary', 'Activity'),
                ]
            )
            ->add(
                'budget_item',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\BudgetItem',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form budget_item'
                    ]
                ]
            )
            ->addAddMoreButton('add_budget_item', 'budget_item');
    }
}
