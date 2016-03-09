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
                    'wrapper'    => ['class' => 'col-xs-12 col-sm-6'],
                    'help_block' => $this->addHelpText('Activity_IatiIdentifier-activity_identifier', false)
                ]
            )
            ->add(
                'identifier_text',
                'static',
                [
                    'tag'           => 'em',
                    'label'         => 'IATI Identifier',
                    'wrapper'       => ['class' => 'col-xs-12 col-sm-6 identifier_text'],
                    'default_value' => 'This will be auto-generated as you fill Activity Identifier.'
                ]
            )
            ->add(
                'alternate_input',
                'static',
                [
                    'tag'           => 'div',
                    'label'         => 'IATI Identifier',
                    'attr'       => ['class' => 'hover_help_text alternate_input'],
                    'wrapper'       => ['class' => 'col-xs-12 col-sm-6 hidden iati_identifier_text'],
                    'help_block' => $this->addHelpText('Activity_IatiIdentifier-text')
                ]
            )
            ->add(
                'iati_identifier_text',
                'text',
                [
                    'label'      => 'IATI Identifier',
                    'rules'      => 'required',
                    'attr'       => ['readonly' => 'readonly', 'class' => 'form-control hover_help_text'],
                    'wrapper'    => ['class' => 'col-xs-12 col-sm-6 hidden'],
                    'help_block' => $this->addHelpText('Activity_IatiIdentifier-text')
                ]
            );
    }
}
