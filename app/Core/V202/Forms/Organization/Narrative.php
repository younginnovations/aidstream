<?php namespace App\Core\V202\Forms\Organization;

use App\Core\Form\BaseForm;

class Narrative extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('narrative', 'text', ['label' => $this->getData('label'), 'rules' => 'required'])
            ->add(
                'language',
                'select',
                [
                    'choices'     => $this->getCodeList('Language', 'Activity'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Language'
                ]
            )
            ->add('new_field', 'text', ['label' => 'New field in 202'])
            ->addRemoveThisButton('remove_from_collection');
    }
}
