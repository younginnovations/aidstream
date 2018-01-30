<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\FormCreator\Organization\RecipientOrgBudgetForm;
use App\Services\Organization\OrganizationManager;
use App\Services\Organization\RecipientOrgBudgetManager;
use App\Services\RequestManager\Organization\CreateOrgRecipientOrgBudgetRequestManager;
use Illuminate\Support\Facades\Gate;
use Session;
use URL;

class RecipientOrganizationBudgetController extends Controller
{

    protected $formBuilder;
    protected $recipientOrgBudgetManager;
    protected $recipientOrgBudgetFormCreator;
    protected $organizationManager;

    public function __construct(
        RecipientOrgBudgetForm $recipientOrgBudgetFormCreator,
        RecipientOrgBudgetManager $recipientOrgBudgetManager,
        OrganizationManager $organizationManager
    ) {
        $this->middleware('auth');
        $this->recipientOrgBudgetFormCreator = $recipientOrgBudgetFormCreator;
        $this->recipientOrgBudgetManager     = $recipientOrgBudgetManager;
        $this->organizationManager           = $organizationManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $organizationId
     * @return Response
     */
    public function index($organizationId)
    {
        $organization = $this->organizationManager->findOrganizationData($organizationId);
        $id           = $organizationId;

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $recipientCountryBudget = $this->recipientOrgBudgetManager->getRecipientOrgBudgetData($organizationId);
        $form                   = $this->recipientOrgBudgetFormCreator->editForm(
            $recipientCountryBudget,
            $organizationId
        );

        return view('Organization.recipientOrgBudget.edit', compact('form', 'recipientCountryBudget', 'organizationId', 'id'));
    }


    /**
     * write brief description
     * @param                                           $orgId
     * @param CreateOrgRecipientOrgBudgetRequestManager $createOrgRecipientOrgBudgetRequestManager
     * @param Request                                   $request
     * @return mixed
     */
    public function update($orgId, CreateOrgRecipientOrgBudgetRequestManager $createOrgRecipientOrgBudgetRequestManager, Request $request)
    {
        $organization = $this->organizationManager->findOrganizationData($orgId);

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($organization, 'recipient_organization_budget');
        $input = $request->all();

        if ($this->recipientOrgBudgetManager->update($input, $organization)) {
            $this->organizationManager->resetStatus($orgId);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('title.org_recipient_organisation_budget')]]];

            return redirect()->to(sprintf('/organization/%s', $orgId))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('title.org_recipient_organisation_budget')]]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
