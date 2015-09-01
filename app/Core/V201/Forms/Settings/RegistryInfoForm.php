<?php namespace App\Core\V201\Forms\Settings;

use Kris\LaravelFormBuilder\Form;

class RegistryInfoForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('publisher id', 'text')
            ->add('api id', 'text')
            ->add('publish files: ', 'choice', [
                'label' => 'Automatically Update the IATI Registry when publishing files:',
                'choices' => ['no' => 'No', 'yes' => 'Yes'],
                'selected' => 'no',
                'expanded' => true,
                'choice_options' => [
                    'wrapper' => ['class' => 'choice-wrapper']
                ]
            ]);
    }
}