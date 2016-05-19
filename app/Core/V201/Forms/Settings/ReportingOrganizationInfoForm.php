<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

class ReportingOrganizationInfoForm extends Baseform
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('reporting_organization_identifier', 'text', ['help_block' => $this->addHelpText('activity_defaults-reporting_org_ref', false), 'required' => true])
            ->addSelect(
                'reporting_organization_type',
                $this->getCodeList('OrganizationType', 'Organization'),
                'Reporting Organization Type',
                $this->addHelpText('activity_defaults-reporting_org_type', false),
                null,
                true
            )
            ->addCollection('narrative', 'Settings\Narrative', 'narrative', ['label' => 'Text'], 'Organization Name')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
