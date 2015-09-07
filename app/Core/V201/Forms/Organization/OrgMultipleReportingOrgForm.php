<?php
namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class OrgMultipleReportingOrgForm extends Form
{

    protected $showFieldErrors = true;

    public function  buildForm()
    {
        $this
            ->add('reportingOrg', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\OrgReportingOrgForm',
                    'label' => false,
                ],
                'label' => false,
            ]);
    }

}