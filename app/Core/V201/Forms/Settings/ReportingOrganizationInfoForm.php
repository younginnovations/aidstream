<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

class ReportingOrganizationInfoForm extends Baseform
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('reporting_organization_identifier', 'text', ['help_block' => $this->addHelpText('activity_defaults-reporting_org_ref', false)])
            ->addSelect(
                'reporting_organization_type',
                $this->getCodeList('OrganizationType', 'Organization'),
                'Reporting Organization Type',
                $this->addHelpText('activity_defaults-reporting_org_type', false)
            )
            ->addCollection('narrative', 'Settings\Narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
