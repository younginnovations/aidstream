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
                     'label'   => trans('setting.save_activities_checklist'),
                     'attr'    => ['class' => 'btn btn-primary btn-submit btn-form'],
                     'wrapper' => ['class' => 'form-group']
                 ]
             );
    }
}