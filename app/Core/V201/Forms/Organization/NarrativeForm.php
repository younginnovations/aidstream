<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class NarrativeForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('narrative', 'text', ['label' => 'Text', 'rules' => 'required'])
            ->add(
                'language',
                'select',
                [
                    'choices' => $this->getCodeList('Language', 'Activity'),
                    'label'   => 'Language'
                ]
            )
            ->removeButton('remove');
    }
}