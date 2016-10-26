<?php namespace App\Core\V201\Forms\Settings;


use App\Core\Form\BaseForm;

class PublishingInfo extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('publisher_id', 'text', ['help_block' => $this->addHelpText('activity_defaults-publisher_id', false), 'wrapper' => ['class' => 'form-group col-md-6']])
            ->add('publisher_id_status', 'text', ['label' => 'Incorrect', 'wrapper' => ['class' => 'hidden']])
            ->add('api_id', 'text', ['help_block' => $this->addHelpText('activity_defaults-api_key', false), 'label' => 'API Key', 'wrapper' => ['class' => 'form-group col-md-6']])
            ->add('api_id_status', 'text', ['label' => 'Incorrect', 'wrapper' => ['class' => 'hidden']])
            ->add(
                'verify',
                'button',
                [
                    'label'   => 'Verify',
                    'attr'    => [
                        'class' => 'btn btn-primary',
                        'id'    => 'verify'
                    ],
                    'help_block' => [
                        'text' => 'Click to verify your registry information',
                        'tag' => 'span',
                        'attr' => ['class' => 'verify-registry']
                    ],
                    'wrapper' => ['class' => 'form-group col-md-6'],
                ]
            )
            ->add(
                'publishing',
                'choice',
                [
                    'label'          => 'Publishing Type for Activities',
                    'choices'        => ['unsegmented' => 'Unsegmented', 'segmented' => 'Segmented'],
                    'expanded'       => true,
                    'default_value'  => 'segmented',
                    'choice_options' => [
                        'wrapper' => ['class' => 'choice-wrapper']
                    ],
                    'wrapper'        => ['class' => 'form-group registry-info-wrapper'],
                    'help_block'     => $this->addHelpText('activity_defaults-publishing_type', false)
                ]
            )
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
            )
            ->add(
                'Save',
                'submit',
                [
                    'label'   => 'Save Publishing Settings',
                    'attr'    => [
                        'class' => 'btn btn-primary btn-submit btn-form'
                    ],
                    'wrapper' => ['class' => 'form-group']
                ]
            );
    }


}