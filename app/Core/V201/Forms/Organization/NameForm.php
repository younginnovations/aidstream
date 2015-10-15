<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class NameForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add(
                'name',
                'collection',
                [
                    'type' => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\NarrativeForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form narrative'
                    ]
                ]
            )
            ->addAddMoreButton('add_name', 'narrative');
    }
}
