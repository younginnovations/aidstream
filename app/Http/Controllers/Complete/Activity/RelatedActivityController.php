<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\RelatedActivityManager;
use App\Services\FormCreator\Activity\RelatedActivity as RelatedActivityForm;
use App\Services\Activity\ActivityManager;
use App\Http\Requests\Request;
use App\Services\RequestManager\Activity\RelatedActivity as RelatedActivityRequestManager;
use Illuminate\Support\Facades\Gate;

/**
 * Class RelatedActivityController
 * @package App\Http\Controllers\Complete\Activity
 */
class RelatedActivityController extends Controller
{

    /**
     * @param RelatedActivityManager $relatedActivityManager
     * @param RelatedActivityForm    $relatedActivityForm
     * @param ActivityManager        $activityManager
     */
    function __construct(RelatedActivityManager $relatedActivityManager, RelatedActivityForm $relatedActivityForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->relatedActivityManager = $relatedActivityManager;
        $this->relatedActivityForm    = $relatedActivityForm;
        $this->activityManager        = $activityManager;
    }

    public function index($id)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $budget       = $this->relatedActivityManager->getRelatedActivityData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->relatedActivityForm->editForm($budget, $id);

        return view('Activity.relatedActivity.edit', compact('form', 'activityData', 'id'));
    }

    public function update($id, Request $request, RelatedActivityRequestManager $relatedActivityRequestManager)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $activityData    = $this->activityManager->getActivityData($id);
        $this->authorizeByRequestType($activityData, 'related_activity');
        $relatedActivity = $request->all();
        if ($this->relatedActivityManager->update($relatedActivity, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Related Activity']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Related Activity']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
