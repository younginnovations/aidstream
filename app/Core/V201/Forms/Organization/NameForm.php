<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class NameForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->addCollection('name', 'Organization\NarrativeForm', 'narrative')
            ->addAddMoreButton('add_name', 'narrative');
    }
}
