<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\DefaultAidTypeManager;
use App\Services\FormCreator\Activity\DefaultAidType as DefaultAidTypeForm;
use App\Services\RequestManager\Activity\DefaultAidType as DefaultAidTypeRequestManager;
use Illuminate\Http\Request;

/**
 * Class DefaultAidTypeController
 * @package App\Http\Controllers\Complete\Activity
 */
class DefaultAidTypeController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var DefaultAidTypeManager
     */
    protected $defaultAidTypeManager;
    /**
     * @var DefaultAidTypeForm
     */
    protected $defaultAidTypeForm;

    /**
     * @param DefaultAidTypeManager $defaultAidTypeManager
     * @param DefaultAidTypeForm    $defaultAidTypeForm
     * @param ActivityManager       $activityManager
     */
    function __construct(DefaultAidTypeManager $defaultAidTypeManager, DefaultAidTypeForm $defaultAidTypeForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager       = $activityManager;
        $this->defaultAidTypeManager = $defaultAidTypeManager;
        $this->defaultAidTypeForm    = $defaultAidTypeForm;
    }

    /**
     * returns the activity default aid type edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function  index($id)
    {
        $defaultAidType = $this->defaultAidTypeManager->getDefaultAidTypeData($id);
        $activityData   = $this->activityManager->getActivityData($id);
        $form           = $this->defaultAidTypeForm->editForm($defaultAidType, $id);

        return view('Activity.defaultAidType.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * updates activity default aid type
     * @param                               $id
     * @param Request                       $request
     * @param DefaultAidTypeRequestManager  $defaultAidTypeRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, DefaultAidTypeRequestManager $defaultAidTypeRequestManager)
    {
        $defaultAidType = $request->all();
        $activityData   = $this->activityManager->getActivityData($id);
        if ($this->defaultAidTypeManager->update($defaultAidType, $activityData)) {
            return redirect()->to(sprintf('/activity/%s', $id))->withMessage('Activity Default Aid Type updated!');
        }

        return redirect()->back();
    }
}
