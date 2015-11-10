<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\PolicyMakerManager;
use App\Services\FormCreator\Activity\PolicyMaker as PolicyMakerForm;
use App\Services\RequestManager\Activity\PolicyMaker as PolicyMakerRequestManager;
use Illuminate\Http\Request;

/**
 * Class PolicyMakerController
 * @package App\Http\Controllers\Complete\Activity
 */
class PolicyMakerController extends Controller
{

    /**
     * @param PolicyMakerManager $policyMakerManager
     * @param PolicyMakerForm    $policyMakerForm
     * @param ActivityManager    $activityManager
     */
    function __construct(PolicyMakerManager $policyMakerManager, PolicyMakerForm $policyMakerForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager    = $activityManager;
        $this->policyMakerForm    = $policyMakerForm;
        $this->policyMakerManager = $policyMakerManager;
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $policyMaker  = $this->policyMakerManager->getPolicyMakerData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->policyMakerForm->editForm($policyMaker, $id);

        return view('Activity.policyMaker.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * @param                           $id
     * @param Request                   $request
     * @param PolicyMakerRequestManager $policyMakerRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, PolicyMakerRequestManager $policyMakerRequestManager)
    {
        $policyMaker  = $request->all();
        $activityData = $this->activityManager->getActivityData($id);
        if ($this->policyMakerManager->update($policyMaker, $activityData)) {
            return redirect()->to(sprintf('/activity/%s', $id))->withMessage('Policy Maker Updated !');
        }

        return redirect()->back();
    }
}
