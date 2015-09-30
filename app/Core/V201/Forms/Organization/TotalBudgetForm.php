<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class TotalBudgetForm extends Form
{
    public function buildForm()
    {
        $this
            ->add(
                'periodStart',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\PeriodStartForm',
                        'label' => false,
                    ]
                ]
            )
            ->add(
                'periodEnd',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\PeriodEndForm',
                        'label' => false,
                    ]
                ]
            )
            ->add(
                'value',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\ValueForm',
                        'label' => false,
                    ]
                ]
            )
            ->add(
                'budgetLine',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\BudgetLineForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form budget_line'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'budget_line'
                    ]
                ]
            )
            ->add(
                'Remove this',
                'button',
                [
                    'attr' => [
                        'class' => 'remove_from_collection',
                    ]
                ]
            );
    }
}