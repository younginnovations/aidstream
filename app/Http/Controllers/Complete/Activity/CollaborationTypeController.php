<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\CollaborationTypeManager;
use App\Services\FormCreator\Activity\CollaborationType as CollaborationTypeForm;
use App\Services\RequestManager\Activity\CollaborationType as CollaborationTypeRequestManager;
use Illuminate\Http\Request;

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
    public function  index($id)
    {
        $collaborationType = $this->collaborationTypeManager->getCollaborationTypeData($id);
        $activityData      = $this->activityManager->getActivityData($id);
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
        $this->authorize(['edit_activity', 'add_activity']);
        $collaborationType = $request->all();
        $activityData      = $this->activityManager->getActivityData($id);
        if ($this->collaborationTypeManager->update($collaborationType, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);

            return redirect()->to(sprintf('/activity/%s', $id))->withMessage('Activity collaboration type updated!');
        }

        return redirect()->back();
    }
}
