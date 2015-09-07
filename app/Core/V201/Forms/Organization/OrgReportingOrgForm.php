<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;
use App\Core\Version;

class OrgReportingOrgForm extends Form
{
    protected $showFieldErrors = true;

    public  function  buildForm()
    {
        $this
            ->add('reporting organization type', 'select', [
                'choices' => ['10' => 'Government', '15' => 'Other Public Sector']
            ])
            ->add('narrative', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\NarrativeForm',
                    'label' => false,
                ]
            ]);
    }
}