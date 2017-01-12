<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\CollaborationTypeManager;
use App\Services\FormCreator\Activity\CollaborationType as CollaborationTypeForm;
use App\Services\RequestManager\Activity\CollaborationType as CollaborationTypeRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class CollaborationTypeController
 * @package App\Http\Controllers\Complete\Activity
 */
class CollaborationTypeController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var CollaborationTypeManager
     */
    protected $collaborationTypeManager;
    /**
     * @var CollaborationTypeForm
     */
    protected $collaborationTypeForm;

    /**
     * @param CollaborationTypeManager $collaborationTypeManager
     * @param CollaborationTypeForm    $collaborationTypeForm
     * @param ActivityManager          $activityManager
     */
    function __construct(CollaborationTypeManager $collaborationTypeManager, CollaborationTypeForm $collaborationTypeForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager          = $activityManager;
        $this->collaborationTypeManager = $collaborationTypeManager;
        $this->collaborationTypeForm    = $collaborationTypeForm;
    }

    /**
     * returns the activity collaboration type edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $collaborationType = $this->collaborationTypeManager->getCollaborationTypeData($id);
        $form              = $this->collaborationTypeForm->editForm($collaborationType, $id);

        return view('Activity.collaborationType.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * updates activity collaboration type
     * @param                                 $id
     * @param Request                         $request
     * @param CollaborationTypeRequestManager $collaborationTypeRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, CollaborationTypeRequestManager $collaborationTypeRequestManager)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityData, 'collaboration_type');
        $collaborationType = $request->all();
        if ($this->collaborationTypeManager->update($collaborationType, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('element.collaboration_type')]]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('element.collaboration_type')]]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
