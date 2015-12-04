<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\TitleManager;
use App\Services\FormCreator\Activity\Title;
use App\Services\RequestManager\Activity\TitleRequestManager;
use Illuminate\Http\Request;

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
        $activityTitle = $this->titleManager->getTitleData($id);
        $form          = $this->title->editForm($activityTitle, $id);

        return view(
            'Activity.title.title',
            compact('form', 'id')
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
        $this->authorize(['edit_activity', 'add_activity']);
        $activityTitle = $request->all();
        $activityData  = $this->titleManager->getActivityData($id);
        if ($this->titleManager->update($activityTitle, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);

            return redirect()->to(sprintf('/activity/%s', $id))->withMessage('Activity Title Updated !');
        }

        return redirect()->back();
    }
}
