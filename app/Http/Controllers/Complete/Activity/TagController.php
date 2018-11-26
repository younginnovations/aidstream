<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\TagManager;
use App\Services\FormCreator\Activity\Tag as TagForm;
use App\Services\RequestManager\Activity\Tag as TagRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class SectorController
 * @package app\Http\Controllers\Complete\Activity
 */
class TagController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var TagForm
     */
    protected $tagForm;
    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     * @param TagManager   $sectorManager
     * @param SectorForm      $sectorForm
     * @param ActivityManager $activityManager
     */
    function __construct(TagManager $tagManager, TagForm $tagForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager = $activityManager;
        $this->tagForm      = $tagForm;
        $this->tagManager   = $tagManager;
    }

    /**
     * returns the activity sector edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $tag       = $this->tagManager->getTagData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->tagForm->editForm($tag, $id);
        return view('Activity.tag.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * updates activity sector
     * @param                      $id
     * @param Request              $request
     * @param SectorRequestManager $sectorRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, TagRequestManager $tagRequestManager)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityData, 'tag');
        $tags = $request->all();

        if ($this->tagManager->update($tags, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('element.tag')]]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('element.tag')]]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
