<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\RequestManager\Organization\CreateOrgRecipientOrgBudgetRequestManager;
use App\Services\FormCreator\Organization\RecipientOrgBudgetForm;
use App\Services\Organization\RecipientOrgBudgetManager;
use Session;
use URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class RecipientOrganizationBudgetController extends Controller
{

    protected $formBuilder;
    protected $recipientOrgBudgetManager;
    protected $recipientOrgBudgetFormCreator;

    public function __construct(
        RecipientOrgBudgetForm $recipientOrgBudgetFormCreator,
        RecipientOrgBudgetManager $recipientOrgBudgetManager
    ) {
        $this->middleware('auth');
        $this->recipientOrgBudgetFormCreator = $recipientOrgBudgetFormCreator;
        $this->recipientOrgBudgetManager     = $recipientOrgBudgetManager;
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

        return view('Organization.recipientOrgBudget.edit', compact('form', 'recipientCountryBudget'));
    }


    /**
     * write brief description
     * @param                                           $orgId
     * @param CreateOrgRecipientOrgBudgetRequestManager $request
     * @param Request                                   $request
     * @return mixed
     */
    public function update(
        $orgId,
        CreateOrgRecipientOrgBudgetRequestManager $request,
        Request $request
    ) {
        $input = $request->all();
        $organizationData = $this->recipientOrgBudgetManager->getOrganizationData($orgId);

        if ($this->recipientOrgBudgetManager->update($input, $organizationData)) {
            return redirect()->to(sprintf('/organization/%s', $orgId))->withMessage(
                'Organization Recipient organization Budget Updated !'
            );
        }

        return redirect()->to->route('organization.recipient-organization-budget.index', $orgId);
    }
}
