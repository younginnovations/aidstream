<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class LanguageCodeForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add(
                'language',
                'select',
                [
                    'choices'     => $this->getCodeList('Language', 'Organization'),
                    'empty_value' => 'Select one of the following option :',
                    'label'       => 'Language'
                ]
            )
            ->addRemoveThisButton('remove_language_code');
    }
}
