<?php
namespace App\Services\FormCreator\Organization;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

class TotalBudgetForm {

    protected $formBuilder;
    protected $version;
    protected $formPath;
    function __construct(FormBuilder $formBuilder,Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->version = $version;
        $this->formPath=$this->version->getOrganizationElement()->getTotalBudget()->getForm();
    }

    public function editForm($data,$organizationId)
    {
        $modal['totalBudget'] = $data;
        return $this->formBuilder->create($this->formPath, [
            'method' => 'PUT',
            'model' => $data['totalBudget'],
            'url' => route('organization.total-budget.update', [$organizationId, 0])
        ])->add('Save', 'submit');
    }
}