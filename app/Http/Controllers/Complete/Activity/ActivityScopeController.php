<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityScopeManager;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\ActivityScope as ActivityScopeForm;
use App\Services\RequestManager\Activity\ActivityScope as ActivityScopeRequestManager;
use App\Http\Requests\Request;

/**
 * Class ActivityScopeController
 * @package app\Http\Controllers\Complete\Activity
 */
class ActivityScopeController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var ActivityScopeForm
     */
    protected $activityScopeForm;
    /**
     * @var ActivityScopeManager
     */
    protected $activityScopeManager;

    /**
     * @param ActivityScopeManager $activityScopeManager
     * @param ActivityScopeForm    $activityScopeForm
     * @param ActivityManager      $activityManager
     */
    function __construct(
        ActivityScopeManager $activityScopeManager,
        ActivityScopeForm $activityScopeForm,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->activityManager      = $activityManager;
        $this->activityScopeForm    = $activityScopeForm;
        $this->activityScopeManager = $activityScopeManager;
    }

    /**
     * returns the activity scope edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityScope = $this->activityScopeManager->getActivityScopeData($id);
        $activityData  = $this->activityManager->getActivityData($id);
        $form          = $this->activityScopeForm->editForm($activityScope, $id);

        return view(
            'Activity.activityScope.edit',
            compact('form', 'activityData', 'id')
        );
    }

    /**
     * updates activity scope
     * @param                             $id
     * @param Request                     $request
     * @param ActivityScopeRequestManager $activityScopeRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, ActivityScopeRequestManager $activityScopeRequestManager)
    {
        $activityData   = $this->activityManager->getActivityData($id);
        $this->authorizeByRequestType($activityData, 'activity_scope');
        $activityStatus = $request->all();
        if ($this->activityScopeManager->update($activityStatus, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Activity Scope']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Activity Scope']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
