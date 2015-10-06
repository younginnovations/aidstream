<?php namespace App\Core\V201\Forms\Organization;

use App\Core\AidStreamForm;

class MultipleTotalBudgetForm extends AidStreamForm
{
    public function buildForm()
    {
        $this
            ->add(
                'totalBudget',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\TotalBudgetForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form total_budget'
                    ]
                ]
            )
            ->addButton('add', 'total_budget');
    }
}