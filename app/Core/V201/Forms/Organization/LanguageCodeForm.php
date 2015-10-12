<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class LanguageCodeForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add(
                'language',
                'select',
                [
                    'choices' => $this->getCodeList('Language', 'Organization'),
                    'label'   => 'Language'
                ]
            )
            ->add(
                'Remove this',
                'button',
                [
                    'attr' => [
                        'class' => 'remove_from_collection',
                    ]
                ]
            );
    }
}
