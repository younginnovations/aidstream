<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class NarrativeForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('narrative', 'text', ['label' => 'Title', 'rules' => 'required'])
            ->add('language', 'select', [
                'choices' => ['es' => 'Espanish', 'fr' => 'French'],
                'label' => 'Language'
            ])
            ->add('add_more_narrative', 'button');
    }
}