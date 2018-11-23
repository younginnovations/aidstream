<?php namespace App\Http\Controllers\Np\Workflow;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\Workflow\WorkflowManager;
use App\Services\RequestManager\ActivityElementValidator;
use App\Http\Controllers\Complete\WorkflowController as MainWorkflowController;

/**
 * Class WorkflowController
 * @package App\Http\Controllers\Np\Workflow
 */
class WorkflowController extends MainWorkflowController
{
    /**
     * WorkflowController constructor.
     * @param WorkflowManager $workflowManager
     */
    public function __construct(WorkflowManager $workflowManager)
    {
        parent::__construct($workflowManager);
        $this->middleware('auth');
        $this->middleware('auth.systemVersion');
    }

    /**
     * Complete an Activity.
     *
     * Changes the ActivityWorkflow status of an Activity to 'Complete'.
     *
     * @param                          $activityId
     * @param Request                  $request
     * @param ActivityElementValidator $activityElementValidator
     * @return mixed
     */
    public function complete($activityId, Request $request, ActivityElementValidator $activityElementValidator)
    {
        return parent::complete($activityId, $request, $activityElementValidator);
    }

    /**
     * Verify an Activity.
     *
     * Changes the ActivityWorkflow status of an Activity to 'Verified'.
     *
     * @param         $activityId
     * @param Request $request
     * @return mixed
     */
    public function verify($activityId, Request $request)
    {
        return parent::verify($activityId, $request);
    }

    /**
     * Publish an Activity.
     *
     * Changes the ActivityWorkflow status of an Activity to 'Published'.
     *
     * @param         $activityId
     * @param Request $request
     * @return mixed
     */
    public function publish($activityId, Request $request)
    {
        $activity = $this->workFlowManager->findActivity($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('publish_activity', $activity);

        if ($this->hasNoPublisherInfo($activity->organization->settings)) {
            return redirect()->route('np.settings.edit')->withResponse(['type' => 'warning', 'code' => ['settings_registry_info', ['name' => '']]]);
        }

        $result = $this->workFlowManager->publish($activity, $request->all());

        if (getVal($result, ['status']) == false) {
            return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => getVal($result, ['message'])]]]);
        }

        if (getVal($result, ['linked']) == false) {
            return redirect()->back()->withResponse(['type' => 'success', 'code' => ['published_not_linked', ['name' => '']]]);
        }

        return redirect()->back()->withResponse(['type' => 'success', 'code' => ['publish_registry_publish', ['name' => '']]]);
    }
}
