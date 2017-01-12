<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\DefaultFlowTypeManager;
use App\Services\FormCreator\Activity\DefaultFlowType as DefaultFlowTypeForm;
use App\Services\RequestManager\Activity\DefaultFlowType as DefaultFlowTypeRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class DefaultFlowTypeController
 * @package App\Http\Controllers\Complete\Activity
 */
class DefaultFlowTypeController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var DefaultFlowTypeManager
     */
    protected $defaultFlowTypeManager;
    /**
     * @var DefaultFlowTypeForm
     */
    protected $defaultFlowTypeForm;

    /**
     * @param DefaultFlowTypeManager $defaultFlowTypeManager
     * @param DefaultFlowTypeForm    $defaultFlowTypeForm
     * @param ActivityManager        $activityManager
     */
    function __construct(DefaultFlowTypeManager $defaultFlowTypeManager, DefaultFlowTypeForm $defaultFlowTypeForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager        = $activityManager;
        $this->defaultFlowTypeManager = $defaultFlowTypeManager;
        $this->defaultFlowTypeForm    = $defaultFlowTypeForm;
    }

    /**
     * returns the activity default flow type edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $defaultFlowType = $this->defaultFlowTypeManager->getDefaultFlowTypeData($id);
        $form            = $this->defaultFlowTypeForm->editForm($defaultFlowType, $id);

        return view('Activity.defaultFlowType.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * updates activity default flow type
     * @param                               $id
     * @param Request                       $request
     * @param DefaultFlowTypeRequestManager $defaultFlowTypeRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, DefaultFlowTypeRequestManager $defaultFlowTypeRequestManager)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityData, 'default_flow_type');
        $defaultFlowType = $request->all();
        if ($this->defaultFlowTypeManager->update($defaultFlowType, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('element.default_flow_type')]]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('element.default_flow_type')]]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
