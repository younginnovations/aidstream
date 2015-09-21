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
        $this->version     = $version;
        $this->formPath    = $this->version->getOrganizationElement()->getName()->getForm();
    }

    public function editForm($data, $organizationId)
    {
        $modal['name'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $modal,
                'url'    => route('organization.name.update', [$organizationId, 0])
            ]
        )->add('Save', 'submit');

    }
}