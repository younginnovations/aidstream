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
                'iati_identifier_text',
                'text',
                [
                    'label'      => 'IATI Identifier',
                    'rules'      => 'required',
                    'attr'       => ['readonly' => 'readonly'],
                    'wrapper'    => ['class' => 'col-xs-12 col-sm-6'],
                    'help_block' => $this->addHelpText('Activity_IatiIdentifier-text')
                ]
            );
    }
}
