<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class ReportingOrganizationInfoForm extends Baseform
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add(
                'reporting_organization_identifier',
                'text',
                [
                    'attr'       => ['readonly' => 'readonly'],
                    'help_block' => $this->addHelpText('Organisation_Identifier-text'),
                    'label'      => 'Reporting Organisation Identifier'
                ]
            )
            ->add(
                'reporting_organization_type',
                'select',
                [
                    'choices'     => $this->getCodeList('OrganizationType', 'Organization'),
                    'empty_value' => 'Select one of the following option :',
                    'attr'        => ['readonly' => 'readonly'],
                    'help_block'  => $this->addHelpText('Organisation_ReportingOrg-type'),
                    'label'      => 'Reporting Organisation Type'
                ]
            )
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
