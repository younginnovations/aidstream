<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\TitleManager;
use App\Services\FormCreator\Activity\Title;
use App\Services\RequestManager\Activity\TitleRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class TitleController
 * Contains functions which allow user to add and update Activity Title
 * @package app\Http\Controllers\Complete\Activity
 */
class TitleController extends Controller
{
    /**
     * @var Title
     */
    protected $title;
    /**
     * @var TitleManager
     */
    protected $titleManager;

    /**
     * @var ActivityManager
     */
    protected $activityManager;


    /**
     * @param TitleManager    $titleManager
     * @param Title           $title
     * @param ActivityManager $activityManager
     */
    function __construct(TitleManager $titleManager, Title $title, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->title           = $title;
        $this->titleManager    = $titleManager;
        $this->activityManager = $activityManager;
    }

    /**
     * returns the activity title edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $activityTitle = $this->titleManager->getTitleData($id);
        $form          = $this->title->editForm($activityTitle, $id);

        return view(
            'Activity.title.title',
            compact('form', 'id', 'activityData')
        );
    }

    /**
     * updates activity title
     * @param                     $id
     * @param Request             $request
     * @param TitleRequestManager $titleRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, TitleRequestManager $titleRequestManager)
    {
        if (!$this->currentUserIsAuthorizedForActivity($id)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $activityData = $this->titleManager->getActivityData($id);
        $this->authorizeByRequestType($activityData, 'title');
        $activityTitle = $request->all();
        if ($this->titleManager->update($activityTitle, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Activity Title']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Activity Title']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
