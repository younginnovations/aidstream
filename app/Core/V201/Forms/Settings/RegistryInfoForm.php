<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

class RegistryInfoForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('publisher_id', 'text', ['help_block' => $this->addHelpText('activity_defaults-publisher_id', false)])
            ->add('api_id', 'text', ['help_block' => $this->addHelpText('activity_defaults-api_key', false)])
            ->add(
                'publish_files',
                'choice',
                [
                    'label'          => 'Automatically Update the IATI Registry when publishing files:',
                    'choices'        => ['no' => 'No', 'yes' => 'Yes'],
                    'expanded'       => true,
                    'default_value'  => 'no',
                    'choice_options' => [
                        'wrapper' => ['class' => 'choice-wrapper']
                    ],
                    'wrapper'        => ['class' => 'form-group registry-info-wrapper'],
                    'help_block'     => $this->addHelpText('activity_defaults-update_registry', false)
                ]
            );
    }
}
