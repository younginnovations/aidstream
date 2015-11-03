<?php namespace App\Core\V201\Forms\Settings;

use App\Core\Form\BaseForm;
use App\Core\Version;

class OrganizationForm extends BaseForm
{
    protected $showFieldErrors = true;
    protected $formPath;

    function __construct(Version $version)
    {
        $this->version  = $version;
        $this->formPath = $this->version->getOrganizationElement()->getName()->getNameForm();
    }

    public function  buildForm()
    {
        $this
            ->add('identifier', 'text')
            ->addCollection('name', 'Organization\NameForm');
    }
}
