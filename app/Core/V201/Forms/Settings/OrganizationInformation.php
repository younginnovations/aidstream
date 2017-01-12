<?php namespace App\Core\V201\Forms\Settings;


use App\Core\Form\BaseForm;

class OrganizationInformation extends BaseForm
{
    public function buildForm()
    {
        $this->addCollection('narrative', 'Settings\Narrative', 'narrative', ['label' => trans('elementForm.organisation_name')], false)
             ->addAddMoreButton('add_narrative', 'narrative', trans('global.add_organisation'));
    }
}