<?php namespace App\Http\Controllers\Complete;

use App\Http\Controllers\Complete\Traits\WorkflowResponses;
use App\Models\Settings;
use App\Http\Requests\Request;
use App\Http\Controllers\Controller;
use App\Services\Workflow\WorkflowManager;
use App\Services\Xml\Providers\XmlServiceProvider;
use App\Services\RequestManager\ActivityElementValidator;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Complete\Traits\HistoryExchangeRates;

/**
 * Class WorkflowController
 * @package App\Http\Controllers\Complete
 */
class WorkflowController extends Controller
{
    use HistoryExchangeRates, WorkflowResponses;

    /**
     * @var XmlServiceProvider
     */
    protected $xmlServiceProvider;

    /**
     * @var WorkFlowManager
     */
    protected $workFlowManager;

    /**
     * WorkflowController constructor.
     * @param WorkflowManager $workFlowManager
     */
    public function __construct(WorkflowManager $workFlowManager)
    {
        $this->middleware('auth');
        $this->middleware('auth.systemVersion');
        $this->workFlowManager = $workFlowManager;
    }

    /**
     * Complete an Activity.
     *
     * Changes the ActivityWorkflow status of an Activity to 'Complete'.
     * @param                          $id
     * @param Request                  $request
     * @param ActivityElementValidator $activityElementValidator
     * @return mixed
     */
    public function complete($id, Request $request, ActivityElementValidator $activityElementValidator)
    {
        $activity = $this->workFlowManager->findActivity($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $activity);

        if ($cannotBeCompletedMessage = $activityElementValidator->validateActivity($activity, $activity->transactions)) {
            return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => $cannotBeCompletedMessage]]]);
        }

        if ($invalidResponse = $this->workFlowManager->validate($activity)) {
            return redirect()->back()->withResponse(['type' => 'danger', 'messages' => $invalidResponse, 'activity' => 'true']);
        }

        return redirect()->back()
                         ->withResponse(
                             $this->respondTo(
                                 $this->workFlowManager->update($request->all(), $activity),
                                 $request->get('activity_workflow')
                             )
                         );
    }

    /**
     * Verify an Activity.
     *
     * Changes the ActivityWorkflow status of an Activity to 'Verified'.
     *
     * @param         $id
     * @param Request $request
     * @return mixed
     */
    public function verify($id, Request $request)
    {
        $activity = $this->workFlowManager->findActivity($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $activity);

        if (($response = $this->exchangeRates($activity)) instanceof \Exception) {
            return redirect()->back()
                             ->withResponse(
                                 [
                                     'type' => 'warning',
                                     'code' => ['message', ['message' => $response->getMessage()]]
                                 ]
                             );
        }

        return redirect()->back()
                         ->withResponse(
                             $this->respondTo(
                                 $this->workFlowManager->update($request->all(), $this->workFlowManager->findActivity($id)),
                                 $request->get('activity_workflow')
                             )
                         );
    }

    /**
     * Publish an Activity.
     * @param         $id
     * @param Request $request
     * @return mixed
     */
    public function publish($id, Request $request)
    {
        $activity = $this->workFlowManager->findActivity($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('publish_activity', $activity);

        if ($this->hasNoPublisherInfo($activity->organization->settings)) {
            return redirect()->route('publishing-settings')->withResponse(['type' => 'warning', 'code' => ['settings_registry_info', ['name' => '']]]);
        }

        $result = $this->workFlowManager->publish($activity, $request->all());

        if (getVal($result, ['status']) === false) {
            return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => getVal($result, ['message'])]]]);
        }

        if (getVal($result, ['linked']) === false) {
            return redirect()->back()->withResponse(['type' => 'success', 'code' => ['published_not_linked', ['name' => '']]]);
        }

        return redirect()->back()->withResponse(['type' => 'success', 'code' => ['publish_registry_publish', ['name' => '']]]);
    }

    /**
     * Check if the Organization's publisher_id and api_id/api_key has been filled out.
     * @param Settings $settings
     * @return bool
     */
    protected function hasNoPublisherInfo(Settings $settings)
    {
        if (!($registryInfo = $settings->registry_info)) {
            return true;
        }

        return (empty(getVal($registryInfo, [0, 'publisher_id'])) || empty(getVal($registryInfo, [0, 'api_id'])));
    }
}
