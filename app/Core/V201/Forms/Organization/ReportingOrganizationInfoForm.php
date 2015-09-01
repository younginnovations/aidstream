<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class ReportingOrganizationInfoForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('reporting organization identifier', 'text')
            ->add('reporting organization type', 'select', [
                'choices' => ['10' => 'Government', '15' => 'Other Public Sector']
            ])
            ->add('organization name', 'text')
            ->add('reporting organization language', 'select', [
                'choices' => ['es' => 'Espanish', 'fr' => 'French']
            ]);
    }
}