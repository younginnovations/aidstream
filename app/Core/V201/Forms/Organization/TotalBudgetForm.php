<?php namespace App\Core\V201\Forms\Organization;

use App\Core\AidStreamForm;

class TotalBudgetForm extends AidStreamForm
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
            ->addButton('add', 'budget_line')
            ->removeButton('remove', 'total_budget');
    }
}