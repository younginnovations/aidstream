<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

class Identifier extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('activity_identifier', 'text', ['wrapper' => ['class' => 'col-xs-6']])
            ->add(
                'iati_identifier_text',
                'text',
                [
                    'label' => 'IATI Identifier',
                    'rules' => 'required',
                    'attr'  => ['readonly' => 'readonly'],
                    'wrapper' => ['class' => 'col-xs-6']
                ]
            );
    }
}
