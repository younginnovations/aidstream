<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\FormCreator\Organization\RecipientRegionBudget;
use App\Services\Organization\OrganizationManager;
use App\Services\Organization\RecipientRegionBudgetManager;
use App\Services\RequestManager\Organization\RecipientRegionBudget as RecipientRegionBudgetRequest;
use Illuminate\Support\Facades\Gate;

/**
 * Class OrgRecipientRegionBudgetController
 * @package App\Http\Controllers\Complete\Organization
 */
class RecipientRegionBudgetController extends Controller
{
    /**
     * @var RecipientRegionBudgetManager
     */
    protected $recipientRegionBudgetManager;
    /**
     * @var RecipientRegionBudget
     */
    protected $recipientRegionBudget;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * @param RecipientRegionBudget        $recipientRegionBudget
     * @param RecipientRegionBudgetManager $recipientRegionBudgetManager
     * @param OrganizationManager          $organizationManager
     */
    public function __construct(RecipientRegionBudget $recipientRegionBudget, RecipientRegionBudgetManager $recipientRegionBudgetManager, OrganizationManager $organizationManager)
    {
        $this->middleware('auth');
        $this->recipientRegionBudget        = $recipientRegionBudget;
        $this->recipientRegionBudgetManager = $recipientRegionBudgetManager;
        $this->organizationManager          = $organizationManager;
    }

    /**
     * @param $orgId
     * @return \Illuminate\View\View
     */
    public function index($orgId)
    {
        $organization = $this->organizationManager->findOrganizationData($orgId);

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $recipientRegionBudget = $this->recipientRegionBudgetManager->getRecipientRegionBudgetData($orgId);
        $form                  = $this->recipientRegionBudget->editForm($recipientRegionBudget, $orgId);

        return view('Organization.recipientRegionBudget.edit', compact('form', 'recipientRegionBudget', 'orgId'));
    }

    /**
     * @param                                      $orgId
     * @param RecipientRegionBudgetRequest         $recipientRegionBudgetRequest
     * @param Request                              $request
     * @return mixed
     */
    public function update($orgId, RecipientRegionBudgetRequest $recipientRegionBudgetRequest, Request $request)
    {
        $organization     = $this->organizationManager->findOrganizationData($orgId);

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($organization, 'recipient_region_budget');
        $input = $request->all();

        if ($this->recipientRegionBudgetManager->update($input, $organization)) {
            $this->organizationManager->resetStatus($orgId);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('title.org_recipient_region_budget')]]];

            return redirect()->to(sprintf('/organization/%s', $orgId))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('title.org_recipient_region_budget')]]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
