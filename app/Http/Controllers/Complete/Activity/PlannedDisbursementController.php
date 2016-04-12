<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\PlannedDisbursementManager;
use App\Services\FormCreator\Activity\PlannedDisbursement as PlannedDisbursementForm;
use App\Services\Activity\ActivityManager;
use App\Http\Requests\Request;
use App\Services\RequestManager\Activity\PlannedDisbursement as PlannedDisbursementRequestManager;
use Illuminate\Support\Facades\Gate;


/**
 * Class PlannedDisbursementController
 * @package App\Http\Controllers\Complete\Activity
 */
class PlannedDisbursementController extends Controller
{

    /**
     * @param PlannedDisbursementManager $plannedDisbursementManager
     * @param PlannedDisbursementForm    $plannedDisbursementForm
     * @param ActivityManager            $activityManager
     */
    function __construct(PlannedDisbursementManager $plannedDisbursementManager, PlannedDisbursementForm $plannedDisbursementForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->plannedDisbursementManager = $plannedDisbursementManager;
        $this->plannedDisbursementForm    = $plannedDisbursementForm;
        $this->activityManager            = $activityManager;
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $plannedDisbursement = $this->plannedDisbursementManager->getPlannedDisbursementData($id);
        $activityData        = $this->activityManager->getActivityData($id);
        $form                = $this->plannedDisbursementForm->editForm($plannedDisbursement, $id);

        return view('Activity.plannedDisbursement.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * @param                                   $id
     * @param Request                           $request
     * @param PlannedDisbursementRequestManager $plannedDisbursementRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, PlannedDisbursementRequestManager $plannedDisbursementRequestManager)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityData, 'planned_disbursement');
        $plannedDisbursement = $request->all();
        if ($this->plannedDisbursementManager->update($plannedDisbursement, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Planned Disbursement']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Planned Disbursement']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
