<?php
namespace App\Services\FormCreator\Organization;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

class DocumentLinkForm {

    protected $formBuilder;
    protected $version;
    protected $formPath;
    function __construct(FormBuilder $formBuilder,Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->version = $version;
        $this->formPath=$this->version->getOrganizationElement()->getDocumentLink()->getForm();
    }

    /**
     * @param array $data
     * @param $organizationId
     * @return $this
     */
    public function editForm($data,$organizationId)
    {
        $modal['documentLink'] = $data;
        return $this->formBuilder->create($this->formPath, [
            'method' => 'PUT',
            'model' => $modal,
            'url' => route('organization.document-link.update', [$organizationId, 0])
        ])->add('Save', 'submit');
    }
}