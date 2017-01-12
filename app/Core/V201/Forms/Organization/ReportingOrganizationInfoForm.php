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
                    'label'      => trans('elementForm.reporting_organisation_identifier')
                ]
            )
            ->add(
                'reporting_organization_type',
                'select',
                [
                    'choices'     => $this->getCodeList('OrganizationType', 'Organization'),
                    'empty_value' => trans('elementForm.select_text'),
                    'attr'        => ['readonly' => 'readonly'],
                    'help_block'  => $this->addHelpText('Organisation_ReportingOrg-type'),
                    'label'       => trans('elementForm.reporting_organisation_type')
                ]
            )
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
