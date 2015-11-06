<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\PlannedDisbursementManager;
use App\Services\FormCreator\Activity\PlannedDisbursement as PlannedDisbursementForm;
use App\Services\Activity\ActivityManager;
use Illuminate\Http\Request;
use App\Services\RequestManager\Activity\PlannedDisbursement as PlannedDisbursementRequestManager;


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
        $budget       = $this->plannedDisbursementManager->getPlannedDisbursementData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->plannedDisbursementForm->editForm($budget, $id);

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
        $plannedDisbursement = $request->all();
        $activityData        = $this->activityManager->getActivityData($id);
        if ($this->plannedDisbursementManager->update($plannedDisbursement, $activityData)) {
            return redirect()->to(sprintf('/activity/%s', $id))->withMessage(
                'Planned Disbursement Updated !'
            );
        }

        return redirect()->back();
    }
}
