<?php namespace App\Core\V201\Forms\Settings;

use Kris\LaravelFormBuilder\Form;
use App\Core\Version;

class OrganizationForm extends Form
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
            ->add(
                'name',
                'collection',
                [
                    'type'      => 'form',
                    'options'   => [
                        'class' => $this->formPath,
                        'label' => false,
                    ]
                ]
            );
    }
}