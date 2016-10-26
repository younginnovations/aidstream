<?php namespace App\Core\V201\Forms\Settings;


use App\Core\Form\BaseForm;

class OrganizationInformation extends BaseForm
{
    public function buildForm()
    {
        $this->addCollection('narrative', 'Settings\Narrative', 'narrative', ['label' => 'Organisation Name'], false)
             ->addAddMoreButton('add_narrative', 'narrative','Add organisation name in another language');
    }
}