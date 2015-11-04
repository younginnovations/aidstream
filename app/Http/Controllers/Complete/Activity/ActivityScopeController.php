<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityScopeManager;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\ActivityScope as ActivityScopeForm;
use App\Services\RequestManager\Activity\ActivityScope as ActivityScopeRequestManager;
use Illuminate\Http\Request;

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
        $activityStatus = $request->all();
        $activityData   = $this->activityManager->getActivityData($id);
        if ($this->activityScopeManager->update($activityStatus, $activityData)) {
            return redirect()->to(sprintf('/activity/%s', $id))->withMessage(
                'Activity Scope Updated!'
            );
        }

        return redirect()->back();
    }
}
