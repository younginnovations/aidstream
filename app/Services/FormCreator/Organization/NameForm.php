<?php
namespace App\Services\FormCreator\Organization;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

class NameForm
{

    protected $formBuilder;
    protected $version;
    protected $formPath;

    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->version = $version;
        $this->formPath = $this->version->getOrganizationElement()->getName()->getForm();
    }

    public function create()
    {
        return $this->formBuilder->create($this->formPath, [
            'method' => 'POST',
            'url' => route('organization.store')
        ])->add('Create', 'submit');
    }

    public function editForm($data, $org)
    {
        return $this->formBuilder->create($this->formPath, [
            'method' => 'PUT',
            'model' => $data,
            'url' => URL::route('organization.update', [$org->id])
        ])->add('Update', 'submit');
    }
}