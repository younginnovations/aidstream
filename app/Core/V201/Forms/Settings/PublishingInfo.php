<?php namespace App\Core\V201\Forms\Settings;


use App\Core\Form\BaseForm;

class PublishingInfo extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'publisher_id',
                'text',
                ['help_block' => $this->addHelpText('activity_defaults-publisher_id', false), 'label' => trans('setting.publisher_id'), 'wrapper' => ['class' => 'form-group col-md-6']]
            )
            ->add('publisher_id_status', 'text', ['label' => trans('setting.incorrect'), 'wrapper' => ['class' => 'hidden']])
            ->add('api_id', 'text', ['help_block' => $this->addHelpText('activity_defaults-api_key', false), 'label' => trans('setting.api_key'), 'wrapper' => ['class' => 'form-group col-md-6']])
            ->add('api_id_status', 'text', ['label' => trans('setting.incorrect'), 'wrapper' => ['class' => 'hidden']])
            ->add(
                'verify',
                'button',
                [
                    'label'      => trans('global.verify'),
                    'attr'       => [
                        'class' => 'btn btn-primary',
                        'id'    => 'verify'
                    ],
                    'help_block' => [
                        'text' => trans('setting.verify_help_text'),
                        'tag'  => 'span',
                        'attr' => ['class' => 'verify-registry']
                    ],
                    'wrapper'    => ['class' => 'form-group col-md-6'],
                ]
            )
            ->add(
                'publishing',
                'choice',
                [
                    'label'          => trans('setting.publishing_type_for_activities'),
                    'choices'        => ['unsegmented' => trans('setting.unsegmented'), 'segmented' => trans('setting.segmented')],
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
                    'label'          => trans('setting.automatic_update'),
                    'choices'        => ['no' => trans('elementForm.no'), 'yes' => trans('elementForm.yes')],
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
                    'label'   => trans('global.save_publishing_settings'),
                    'attr'    => [
                        'class' => 'btn btn-primary btn-submit btn-form'
                    ],
                    'wrapper' => ['class' => 'form-group']
                ]
            );
    }
}
