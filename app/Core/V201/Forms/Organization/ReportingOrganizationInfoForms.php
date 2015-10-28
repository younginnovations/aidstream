<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class ReportingOrganizationInfoForms extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('reporting_org', 'Organization\ReportingOrganizationInfoForm');
    }
}
