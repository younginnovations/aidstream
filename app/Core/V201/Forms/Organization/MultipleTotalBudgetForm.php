<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class MultipleTotalBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'totalBudget',
                'collection',
                [
                    'type' => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\TotalBudgetForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form total_budget'
                    ]
                ]
            )
            ->addAddMoreButton('add', 'total_budget');
    }
}
