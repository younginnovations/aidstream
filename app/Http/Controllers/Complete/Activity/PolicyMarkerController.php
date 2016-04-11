<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\PolicyMarkerManager;
use App\Services\FormCreator\Activity\PolicyMarker as PolicyMarkerForm;
use App\Services\RequestManager\Activity\PolicyMarker as PolicyMarkerRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class PolicyMarkerController
 * @package App\Http\Controllers\Complete\Activity
 */
class PolicyMarkerController extends Controller
{

    /**
     * @param PolicyMarkerManager $policyMarkerManager
     * @param PolicyMarkerForm    $policyMarkerForm
     * @param ActivityManager     $activityManager
     */
    function __construct(PolicyMarkerManager $policyMarkerManager, PolicyMarkerForm $policyMarkerForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager     = $activityManager;
        $this->policyMarkerForm    = $policyMarkerForm;
        $this->policyMarkerManager = $policyMarkerManager;
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

        $policyMarker = $this->policyMarkerManager->getPolicyMarkerData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->policyMarkerForm->editForm($policyMarker, $id);

        return view('Activity.policyMarker.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * @param                            $id
     * @param Request                    $request
     * @param PolicyMarkerRequestManager $policyMarkerRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, PolicyMarkerRequestManager $policyMarkerRequestManager)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $activityData = $this->activityManager->getActivityData($id);
        $this->authorizeByRequestType($activityData, 'policy_marker');
        $policyMarker = $request->all();
        if ($this->policyMarkerManager->update($policyMarker, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Policy Marker']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Policy Marker']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
