<?php namespace App\Core\V201\Forms\Organization;

use App\Core\AidStreamForm;

class BudgetLineForm extends AidStreamForm
{
    public function buildForm()
    {
        $this
            ->add('reference', 'text')
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
                'narrative',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\NarrativeForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form budget_line_narrative'
                    ]
                ]
            )
            ->addButton('add', 'budget_line_narrative')
            ->removeButton('remove', 'total_budget');
    }
}