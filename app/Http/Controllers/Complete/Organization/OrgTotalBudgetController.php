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

class OrgTotalBudgetController extends Controller {

    protected $formBuilder;
    protected $totalBudgetManager;
    protected $totalBudgetForm;
    public function __construct(
        FormBuilder $formBuilder,
        OrgTotalBudgetManager $totalBudgetManager
    )
    {
        $this->middleware('auth');
        $this->totalBudgetForm = $formBuilder;
        $this->totalBudgetManager = $totalBudgetManager;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($orgId)
    {
        $totalBudget = $this->totalBudgetManager->getOrganizationTotalBudgetData($orgId);
        $form = $this->totalBudgetForm->editForm($totalBudget, $orgId);
        return view('Organization.totalBudget.totalBudget', compact('form', 'totalBudget'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($orgId, TotalBudgetRequestManager $totalBudgetRequestManager)
    {
        $input = Input::all();
        $organizationData = $this->totalBudgetManager->getOrganizationData($orgId);
        $this->totalBudgetManager->update($input, $organizationData);
        Session::flash('message', 'Total Budget Updated !');
        return Redirect::to("/organization/$orgId");
    }

}
