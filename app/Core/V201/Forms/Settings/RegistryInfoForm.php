<?php namespace App\Core\V201\Forms\Settings;

use Kris\LaravelFormBuilder\Form;

class RegistryInfoForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('publisher_id', 'text')
            ->add('api_id', 'text')
            ->add(
                'publish_files: ',
                'choice',
                [
                    'label'          => 'Automatically Update the IATI Registry when publishing files:',
                    'choices'        => ['no' => 'No', 'yes' => 'Yes'],
                    'expanded'       => true,
                    'choice_options' => [
                        'wrapper' => ['class' => 'choice-wrapper']
                    ],
                    'wrapper' => ['class' => 'form-group registry-info-wrapper']
                ]
            );
    }
}
