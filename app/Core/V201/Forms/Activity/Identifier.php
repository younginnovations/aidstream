<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

class Identifier extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add(
                'activity_identifier',
                'text',
                [
                    'label'       => trans('elementForm.activity_identifier'),
                    'wrapper'    => ['class' => 'col-xs-12 col-sm-6'],
                    'attr'       => ['class' => 'noSpace'],
                    'required'   => true,
                    'help_block' => $this->addHelpText('Activity_IatiIdentifier-activity_identifier', false)
                ]
            )
            ->add(
                'identifier_text',
                'static',
                [
                    'tag'           => 'em',
                    'label'         => trans('elementForm.iati_identifier'),
                    'wrapper'       => ['class' => 'col-xs-12 col-sm-6 identifier_text'],
                    'default_value' => trans('elementForm.auto_generated_identifier')
                ]
            )
            ->add(
                'alternate_input',
                'static',
                [
                    'tag'        => 'div',
                    'label'      => trans('elementForm.iati_identifier'),
                    'attr'       => ['class' => 'hover_help_text alternate_input'],
                    'wrapper'    => ['class' => 'col-xs-12 col-sm-6 hidden iati_identifier_text'],
                    'help_block' => $this->addHelpText('Activity_IatiIdentifier-text')
                ]
            )
            ->add(
                'iati_identifier_text',
                'text',
                [
                    'label'      => trans('elementForm.iati_identifier'),
                    'rules'      => 'required',
                    'attr'       => ['readonly' => 'readonly', 'class' => 'form-control hover_help_text'],
                    'wrapper'    => ['class' => 'col-xs-12 col-sm-6 hidden'],
                    'help_block' => $this->addHelpText('Activity_IatiIdentifier-text')
                ]
            );
    }
}
