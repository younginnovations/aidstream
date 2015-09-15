<?php
/**
 * Created by PhpStorm.
 * User: kriti
 * Date: 9/8/15
 * Time: 1:46 PM
 */
namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Organization\OrgTotalBudgetManager;
use Session;
use URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Services\RequestManager\Organization\TotalBudgetRequestManager;
use App\Services\FormCreator\Organization\TotalBudgetForm as FormBuilder;

/**
 * Class OrgTotalBudgetController
 * @package App\Http\Controllers\Complete\Organization
 */
class OrgTotalBudgetController extends Controller
{

    protected $formBuilder;
    protected $totalBudgetManager;
    protected $totalBudgetForm;

    public function __construct(
        FormBuilder $formBuilder,
        OrgTotalBudgetManager $totalBudgetManager
    ) {
        $this->middleware('auth');
        $this->totalBudgetForm    = $formBuilder;
        $this->totalBudgetManager = $totalBudgetManager;
    }

    /**
     * @param $orgId
     * @return \Illuminate\View\View
     */
    public function index($orgId)
    {
        $totalBudget = $this->totalBudgetManager->getOrganizationTotalBudgetData($orgId);
        $form        = $this->totalBudgetForm->editForm($totalBudget, $orgId);

        return view('Organization.totalBudget.totalBudget', compact('form', 'totalBudget'));
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
        $input            = $request->all();
        $organizationData = $this->totalBudgetManager->getOrganizationData($orgId);

        if ($this->totalBudgetManager->update($input, $organizationData)) {
            return redirect()->to(sprintf('/organization/%s', $orgId))->withMessage(
                'Organization Total Budget Updated !'
            );
        }

        return redirect()->to->route('organization.total-budget.index', $orgId);
    }
}
