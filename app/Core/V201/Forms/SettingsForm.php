<?php namespace App\Core\V201\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Core\Version;
use DB;

class SettingsForm extends Form
{
    protected $showFieldErrors = true;
    protected $version;
    protected $versionForm;
    protected $formPath;
    protected $publishingType;
    protected $registryInfo;
    protected $defaultFieldValues;
    protected $defaultFieldGroups;
    protected $versions;

    function __construct(Version $version) {
        $this->version = $version;
        $this->versionForm = $this->version->getSettingsElement()->getVersionInfo()->getVersionInfoFrom();
        $this->formPath = $this->version->getSettingsElement()->getReportingOrganizationInfo()->getReportingOrganizationInfoForm();
        $this->publishingType = $this->version->getSettingsElement()->getPublishingType()->getPublishingTypeForm();
        $this->registryInfo = $this->version->getSettingsElement()->getregistryInfo()->getregistryInfoForm();
        $this->defaultFieldValues = $this->version->getSettingsElement()->getdefaultFieldValues()->getdefaultFieldValuesForm();
        $this->defaultFieldGroups = $this->version->getSettingsElement()->getdefaultFieldGroups()->getdefaultFieldGroupsForm();
        $db_versions = DB::table('versions')->get();
        $versions = [];
        foreach($db_versions as $ver) {
            $versions[$ver->version] = $ver->version;
        }
        $this->versions = $versions;
    }

    public function buildForm()
    {
        $this
            ->add('version_form', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => $this->versionForm,
                    'data' => ['versions' => $this->versions],
                    'label' => false
                ],
                'label' => false
            ])
            ->add('reporting_organization_info', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => $this->formPath,
                    'label' => false
                ],
                'label' => false
            ])
            ->add('publishing_type', 'collection',[
                'type' => 'form',
                'prototype' => 'true',
                'options' => [
                    'class' => $this->publishingType,
                    'label' => false,
                ],
                'label' => false
            ])
            ->add('registry_info', 'collection',[
                'type' => 'form',
                'prototype' => 'true',
                'options' => [
                    'class' => $this->registryInfo,
                    'label' => false,
                ],
                'label' => false
            ])
            ->add('default_field_values', 'collection',[
                'type' => 'form',
                'prototype' => 'true',
                'options' => [
                    'class' => $this->defaultFieldValues,
                    'label' => false,
                ],
                'label' => false
            ])
            ->add('default_field_groups', 'collection',[
                'type' => 'form',
                'prototype' => 'true',
                'options' => [
                    'class' => $this->defaultFieldGroups,
                    'label' => false,
                ],
                'label' => false
            ])
            ->add('Save','submit', [
                'attr' => ['class' => 'btn btn-primary']
            ]);

    }
}