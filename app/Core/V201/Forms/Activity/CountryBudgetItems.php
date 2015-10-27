<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

class CountryBudgetItems extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'country_budget_item',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Activity\CountryBudgetItem',
                        'label' => false,
                    ],
                ]
            );
    }
}
