<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

class ReportingOrganizationInfoForm extends Baseform
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('reporting_organization_identifier', 'text')
            ->addSelect('reporting_organization_type', $this->getCodeList('OrganizationType', 'Organization'))
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}
