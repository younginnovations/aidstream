<?php namespace App\Core\V201\Forms\Settings;


use App\Core\Form\BaseForm;

class ActivityElementsChecklist extends BaseForm
{
    public function buildForm()
    {
        $this->addCollection('default_field_groups', 'Settings\DefaultFieldGroupsForm')
             ->add(
                 'save',
                 'submit',
                 [
                     'label'   => 'Save',
                     'attr'    => ['class' => 'btn btn-primary'],
                     'wrapper' => ['class' => 'form-group']
                 ]
             );
    }
}