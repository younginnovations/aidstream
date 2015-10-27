<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class ReportingOrganizationInfoForm extends Baseform
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
                    'choices' => $this->addCodeList('OrganizationType', 'Organization')
                ]
            )
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative');
    }
}