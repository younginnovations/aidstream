<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
use App\Services\Organization\OrgTotalBudgetManager;
use App\Http\Requests\Request;
use App\Services\RequestManager\Organization\TotalBudgetRequestManager;
use App\Services\FormCreator\Organization\TotalBudgetForm as FormBuilder;
use Illuminate\Support\Facades\Gate;

/**
 * Class OrgTotalBudgetController
 * @package App\Http\Controllers\Complete\Organization
 */
class OrgTotalBudgetController extends Controller
{

    protected $formBuilder;
    protected $totalBudgetManager;
    protected $totalBudgetForm;
    protected $organizationManager;

    public function __construct(
        FormBuilder $formBuilder,
        OrgTotalBudgetManager $totalBudgetManager,
        OrganizationManager $organizationManager
    ) {
        $this->middleware('auth');
        $this->totalBudgetForm     = $formBuilder;
        $this->totalBudgetManager  = $totalBudgetManager;
        $this->organizationManager = $organizationManager;
    }

    /**
     * @param $orgId
     * @return \Illuminate\View\View
     */
    public function index($orgId)
    {
        $organization = $this->organizationManager->getOrganization($orgId);
        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }
        $totalBudget = $this->totalBudgetManager->getOrganizationTotalBudgetData($orgId);
        $form        = $this->totalBudgetForm->editForm($totalBudget, $orgId);

        return view('Organization.totalBudget.totalBudget', compact('form', 'totalBudget','orgId'));
    }

    /**
     * write brief description
     * @param                           $orgId
     * @param TotalBudgetRequestManager $totalBudgetRequestManager
     * @param Request                   $request
     * @return mixed
     */
    public function update($orgId, TotalBudgetRequestManager $totalBudgetRequestManager, Request $request)
    {
        $organization = $this->organizationManager->getOrganization($orgId);
        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $organizationData = $this->totalBudgetManager->getOrganizationData($orgId);
        $this->authorizeByRequestType($organizationData, 'total_budget');
        $input            = $request->all();

        if ($this->totalBudgetManager->update($input, $organizationData)) {
            $this->organizationManager->resetStatus($orgId);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Organization Total Budget']]];

            return redirect()->to(sprintf('/organization/%s', $orgId))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Organization Total Budget']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
