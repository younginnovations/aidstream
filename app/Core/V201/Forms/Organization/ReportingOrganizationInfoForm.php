<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class ReportingOrganizationInfoForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('reporting_organization_identifier', 'text')
            ->add(
                'reporting_organization_type',
                'select',
                [
                    'choices' => ['10' => 'Government', '15' => 'Other Public Sector']
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
                        'class' => 'collection_form narrative'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'narrative'
                    ]
                ]
            );
    }
}