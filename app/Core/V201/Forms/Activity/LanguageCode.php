<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

class LanguageCode extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add(
                'language',
                'select',
                [
                    'choices' => $this->getCodeList('Language', 'Activity'),
                    'label'   => 'Language'
                ]
            )
            ->addRemoveThisButton('remove_language_code');
    }
}
