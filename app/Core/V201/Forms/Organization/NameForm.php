<?php namespace App\app\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class NameForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('narrative', 'text')
            ->add('language', 'select', [
                'choices' => ['es' => 'Espanish', 'fr' => 'French']
            ]);
    }
}