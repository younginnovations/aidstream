<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\Organization\OrganizationManager;
use App\Services\RequestManager\Organization\CreateOrgRecipientOrgBudgetRequestManager;
use App\Services\FormCreator\Organization\RecipientOrgBudgetForm;
use App\Services\Organization\RecipientOrgBudgetManager;
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
     * @return Response
     */
    public function index($organizationId)
    {
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
        $input            = $request->all();
        $organizationData = $this->recipientOrgBudgetManager->getOrganizationData($orgId);

        if ($this->recipientOrgBudgetManager->update($input, $organizationData)) {
            $this->organizationManager->resetStatus($orgId);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Organization Recipient Organization Budget']]];

            return redirect()->to(sprintf('/organization/%s', $orgId))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Organization Recipient Organization Budget']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
