<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\LegacyDataManager;
use App\Services\FormCreator\Activity\LegacyData as LegacyDataForm;
use App\Services\Activity\ActivityManager;
use App\Http\Requests\Request;
use App\Services\RequestManager\Activity\LegacyData as LegacyDataRequestManager;

/**
 * Class LegacyDataController
 * @package App\Http\Controllers\Complete\Activity
 */
class LegacyDataController extends Controller
{

    /**
     * @var LegacyDataManager
     */
    protected $legacyDataManager;

    /**
     * @var LegacyDataForm
     */
    protected $legacyDataForm;

    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * LegacyDataController constructor.
     * @param LegacyDataManager $legacyDataManager
     * @param LegacyDataForm    $legacyDataForm
     * @param ActivityManager   $activityManager
     */
    function __construct(LegacyDataManager $legacyDataManager, LegacyDataForm $legacyDataForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->legacyDataManager = $legacyDataManager;
        $this->legacyDataForm    = $legacyDataForm;
        $this->activityManager   = $activityManager;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        if (!$this->currentUserIsAuthorizedForActivity($id)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $legacyData   = $this->legacyDataManager->getLegacyData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->legacyDataForm->editForm($legacyData, $id);

        return view('Activity.legacyData.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * @param                          $id
     * @param Request                  $request
     * @param LegacyDataRequestManager $legacyDataRequestManager
     * @return mixed
     */
    public function update($id, Request $request, LegacyDataRequestManager $legacyDataRequestManager)
    {
        if (!$this->currentUserIsAuthorizedForActivity($id)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $activityData = $this->activityManager->getActivityData($id);
        $this->authorizeByRequestType($activityData, 'legacy_data');
        $legacyData   = $request->all();
        if ($this->legacyDataManager->update($legacyData, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Legacy Data']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Legacy Data']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
