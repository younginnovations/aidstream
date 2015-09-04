<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class ReportingOrganizationInfoForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('reporting_organization_identifier', 'text')
            ->add('reporting_organization_type', 'select', [
                'choices' => ['10' => 'Government', '15' => 'Other Public Sector']
            ])
            ->add('organization_name', 'text')
            ->add('reporting_organization_language', 'select', [
                'choices' => ['es' => 'Espanish', 'fr' => 'French']
            ]);
    }
}