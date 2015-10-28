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
                    'choices' => $this->addCodeList('Language', 'Organization'),
                    'label'   => 'Language'
                ]
            )
            ->addRemoveThisButton('remove_language_code');
    }
}
