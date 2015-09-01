<?php namespace App\Core\V201\Forms\Settings;

use Kris\LaravelFormBuilder\Form;

class VersionInfoForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('version', 'select', [
                'label' => 'Select Version',
                'choices' => $this->getData('versions')
            ]);
    }
}