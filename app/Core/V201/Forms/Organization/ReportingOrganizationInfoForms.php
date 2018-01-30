<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class ReportingOrganizationInfoForms extends BaseForm
{
    public function buildForm()
    {
        $this->add(
            'reporting_org',
            'text',
            [
                'attr'       => ['readonly' => 'readonly'],
                'help_block' => $this->addHelpText('Organisation_Identifier-text'),
                'label'      => trans('elementForm.reporting_organisation_identifier')
            ]
        );
//        $this
//            ->addCollection('reporting_org', 'Organization\ReportingOrganizationInfoForm');
    }
}
