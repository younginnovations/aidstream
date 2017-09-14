<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\FormCreator\Organization\RecipientCountryBudgetForm as FormBuilder;
use App\Services\Organization\OrganizationManager;
use App\Services\Organization\RecipientCountryBudgetManager;
use App\Services\RequestManager\Organization\RecipientCountryBudgetRequestManager;
use Illuminate\Support\Facades\Gate;
use Session;
use URL;

/**
 * Class OrgRecipientCountryBudgetController
 * @package App\Http\Controllers\Complete\Organization
 */
class RecipientCountryBudgetController extends Controller
{

    protected $formBuilder;
    protected $recipientCountryBudgetManager;
    protected $recipientCountryBudgetForm;
    protected $organizationManager;

    public function __construct(FormBuilder $formBuilder, RecipientCountryBudgetManager $recipientCountryBudgetManager, OrganizationManager $organizationManager)
    {
        $this->middleware('auth');
        $this->recipientCountryBudgetForm    = $formBuilder;
        $this->recipientCountryBudgetManager = $recipientCountryBudgetManager;
        $this->organizationManager           = $organizationManager;
    }

    /**
     * @param $orgId
     * @return \Illuminate\View\View
     */
    public function index($orgId)
    {
        $organization = $this->organizationManager->findOrganizationData($orgId);
        $id           = $orgId;

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $recipientCountryBudget = $this->recipientCountryBudgetManager->getRecipientCountryBudgetData($orgId);
        $form                   = $this->recipientCountryBudgetForm->editForm($recipientCountryBudget, $orgId);

        return view(
            'Organization.recipientCountryBudget.recipientCountryBudget',
            compact('form', 'recipientCountryBudget', 'orgId', 'id')
        );
    }

    /**
     * @param                                      $orgId
     * @param RecipientCountryBudgetRequestManager $recipientCountryBudgetRequestManager
     * @param Request                              $request
     * @return mixed
     */
    public function update($orgId, RecipientCountryBudgetRequestManager $recipientCountryBudgetRequestManager, Request $request)
    {
        $organization = $this->organizationManager->findOrganizationData($orgId);
        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $organizationData = $this->recipientCountryBudgetManager->getOrganizationData($orgId);
        $this->authorizeByRequestType($organizationData, 'recipient_country_budget');
        $input = $request->all();

        if ($this->recipientCountryBudgetManager->update($input, $organizationData)) {
            $this->organizationManager->resetStatus($orgId);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('title.org_recipient_country_budget')]]];

            return redirect()->to(sprintf('/organization/%s', $orgId))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('title.org_recipient_country_budget')]]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
