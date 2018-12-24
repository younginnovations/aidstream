<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\DefaultAidTypeManager;
use App\Services\FormCreator\Activity\DefaultAidType as DefaultAidTypeForm;
use App\Services\RequestManager\Activity\DefaultAidType as DefaultAidTypeRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

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
    public function index($id)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $defaultAidType = $this->defaultAidTypeManager->getDefaultAidTypeData($id);
        if(session('version') == 'V203') {
            if(!is_array($defaultAidType)) {
                $defaultAidType = [
                    'default_aid_type' => $defaultAidType,
                    'default_aidtype_vocabulary' => '1',
                    'earmarking_category' => '',
                    'default_aid_type_text' => ''
                ];
                $defaultAidType = [$defaultAidType];
            }
        }

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
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityData, 'default_aid_type');
        $defaultAidType = $request->all();
        if(session('version') == 'V203'){
            $defaultAidType['default_aid_type'] = array_map("unserialize", array_unique(array_map("serialize", $defaultAidType['default_aid_type'])));
        }

        if ($this->defaultAidTypeManager->update($defaultAidType, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('element.default_aid_type')]]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('element.default_aid_type')]]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
