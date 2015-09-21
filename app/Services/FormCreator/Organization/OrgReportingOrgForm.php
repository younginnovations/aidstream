<?php
namespace App\Services\FormCreator\Organization;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

class OrgReportingOrgForm
{

    protected $formBuilder;
    protected $version;
    protected $formPath;

    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->version     = $version;
        $this->formPath    = $this->version->getOrganizationElement()->getOrgReportingOrg()->getForm();
    }

    public function create($organizationId)
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'POST',
                'url'    => URL::route('organization.reportingOrg.store', $organizationId)
            ]
        )->add('Create', 'submit');
    }

    public function editForm($data, $organization)
    {
        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $data,
                'url'    => URL::route('organization.reportingOrg.update', [$organization->id, '0'])
            ]
        )->add('Update', 'submit');

    }
}