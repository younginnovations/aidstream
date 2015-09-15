<?php
namespace App\Services\FormCreator\Organization;

use App\Core\Version;
use Kris\LaravelFormBuilder\FormBuilder;
use URL;

class RecipientOrgBudgetForm
{

    protected $formBuilder;
    protected $version;
    protected $formPath;

    function __construct(FormBuilder $formBuilder, Version $version)
    {
        $this->formBuilder = $formBuilder;
        $this->version     = $version;
        $this->formPath    = $this->version->getOrganizationElement()->getRecipientOrgBudget()->getForm();
    }

    public function editForm($data, $organizationId)
    {
        $modal['recipientOrganizationBudget'] = $data;

        return $this->formBuilder->create(
            $this->formPath,
            [
                'method' => 'PUT',
                'model'  => $modal,
                'url'    => URL::route('organization.recipient-organization-budget.update', [$organizationId, '0'])
            ]
        )->add('Update', 'submit');

    }


}