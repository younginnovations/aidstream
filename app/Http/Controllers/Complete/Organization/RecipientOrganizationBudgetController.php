<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\Organization\OrganizationManager;
use App\Services\RequestManager\Organization\CreateOrgRecipientOrgBudgetRequestManager;
use App\Services\FormCreator\Organization\RecipientOrgBudgetForm;
use App\Services\Organization\RecipientOrgBudgetManager;
use Illuminate\Support\Facades\Gate;
use Session;
use URL;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

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
        $organization = $this->organizationManager->getOrganization($organizationId);
        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $recipientCountryBudget = $this->recipientOrgBudgetManager->getRecipientOrgBudgetData($organizationId);
        $form                   = $this->recipientOrgBudgetFormCreator->editForm(
            $recipientCountryBudget,
            $organizationId
        );

        return view('Organization.recipientOrgBudget.edit', compact('form', 'recipientCountryBudget','organizationId'));
    }


    /**
     * write brief description
     * @param                                                   $orgId
     * @param CreateOrgRecipientOrgBudgetRequestManager         $request
     * @param CreateOrgRecipientOrgBudgetRequestManager|Request $request
     * @return mixed
     */
    public function update($orgId, CreateOrgRecipientOrgBudgetRequestManager $request, Request $request)
    {
        $organization = $this->organizationManager->getOrganization($orgId);
        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $organizationData = $this->recipientOrgBudgetManager->getOrganizationData($orgId);
        $this->authorizeByRequestType($organizationData, 'recipient_organization_budget');
        $input            = $request->all();

        if ($this->recipientOrgBudgetManager->update($input, $organizationData)) {
            $this->organizationManager->resetStatus($orgId);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Organization Recipient Organization Budget']]];

            return redirect()->to(sprintf('/organization/%s', $orgId))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Organization Recipient Organization Budget']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
