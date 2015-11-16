<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\LegacyDataManager;
use App\Services\FormCreator\Activity\LegacyData as LegacyDataForm;
use App\Services\Activity\ActivityManager;
use Illuminate\Http\Request;
use App\Services\RequestManager\Activity\LegacyData as LegacyDataRequestManager;

/**
 * Class LegacyDataController
 * @package App\Http\Controllers\Complete\Activity
 */
class LegacyDataController extends Controller
{

    function __construct(LegacyDataManager $legacyDataManager, LegacyDataForm $legacyDataForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->legacyDataManager = $legacyDataManager;
        $this->legacyDataForm    = $legacyDataForm;
        $this->activityManager   = $activityManager;
    }

    public function index($id)
    {
        $legacyData   = $this->legacyDataManager->getLegacyData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->legacyDataForm->editForm($legacyData, $id);

        return view('Activity.legacyData.edit', compact('form', 'activityData', 'id'));
    }

    public function update($id, Request $request, LegacyDataRequestManager $legacyDataRequestManager)
    {
        $legacyData   = $request->all();
        $activityData = $this->activityManager->getActivityData($id);
        if ($this->legacyDataManager->update($legacyData, $activityData)) {
            return redirect()->to(sprintf('/activity/%s', $id))->withMessage('Legacy Data Updated !');
        }

        return redirect()->back();
    }
}
