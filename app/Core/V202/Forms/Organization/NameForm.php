<?php namespace App\Core\V202\Forms\Organization;

use App\Core\Form\BaseForm;

class NameForm extends BaseForm
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->addCollection('name', 'Organization\Narrative', 'narrative')
            ->addAddMoreButton('add_name', 'narrative');
    }
}
