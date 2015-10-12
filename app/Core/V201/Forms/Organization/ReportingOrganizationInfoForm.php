<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class ReportingOrganizationInfoForm extends BaseForm
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
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
