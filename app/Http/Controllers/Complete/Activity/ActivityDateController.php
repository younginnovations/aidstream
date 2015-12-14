<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityDateManager;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\ActivityDate as ActivityDateForm;
use App\Services\RequestManager\Activity\ActivityDate as ActivityDateRequestManager;
use Illuminate\Http\Request;

/**
 * Class ActivityDateController
 * @package app\Http\Controllers\Complete\Activity
 */
class ActivityDateController extends Controller
{
    /**
     * @var ActivityDateForm
     */
    protected $activityDateForm;
    /**
     * @var ActivityDateManager
     */
    protected $activityDateManager;
    protected $activityManager;

    /**
     * @param ActivityDateManager $activityDateManager
     * @param ActivityDateForm    $activityDateForm
     * @param ActivityManager     $activityManager
     */
    function __construct(
        ActivityDateManager $activityDateManager,
        ActivityDateForm $activityDateForm,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->activityManager     = $activityManager;
        $this->activityDateForm    = $activityDateForm;
        $this->activityDateManager = $activityDateManager;
    }

    /**
     * returns the activity date edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityDate = $this->activityDateManager->getActivityDateData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->activityDateForm->editForm($activityDate, $id);

        return view(
            'Activity.activityDate.edit',
            compact('form', 'activityData', 'id')
        );
    }

    /**
     * updates activity description
     * @param                            $id
     * @param Request                    $request
     * @param ActivityDateRequestManager $activityDateRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, ActivityDateRequestManager $activityDateRequestManager)
    {
        $this->authorize(['edit_activity', 'add_activity']);
        $activityDate = $request->all();
        $activityData = $this->activityManager->getActivityData($id);
        if ($this->activityDateManager->update($activityDate, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Activity Date']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Activity Date']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
