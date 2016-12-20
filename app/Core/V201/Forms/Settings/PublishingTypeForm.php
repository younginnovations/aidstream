<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;

class PublishingTypeForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add(
                'publishing',
                'choice',
                [
                    'label'          => trans('setting.publishing_type'),
                    'choices'        => ['unsegmented' => trans('setting.unsegmented'), 'segmented' => trans('setting.segmented')],
                    'expanded'       => true,
                    'choice_options' => [
                        'wrapper' => ['class' => 'choice-wrapper']
                    ],
                    'wrapper'        => ['class' => 'form-group form-choice-wrapper settings-choice-wrapper'],
                    'help_block'     => $this->addHelpText('activity_defaults-publishing_type', false)
                ]
            );
    }
}
