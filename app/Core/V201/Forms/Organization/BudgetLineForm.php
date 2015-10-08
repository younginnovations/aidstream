<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class BudgetLineForm extends BaseForm
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
            ->addAddMoreButton('add', 'budget_line_narrative')
            ->addRemoveThisButton('remove');
    }
}
