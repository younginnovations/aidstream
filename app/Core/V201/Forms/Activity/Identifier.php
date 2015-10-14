<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

class Identifier extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('activity_identifier', 'text')
            ->add(
                'iati_identifier_text',
                'text',
                [
                    'label' => 'IATI Identifier',
                    'rules' => 'required'
                ]
            );
    }
}
