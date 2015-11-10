<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\DefaultFinanceTypeManager;
use App\Services\FormCreator\Activity\DefaultFinanceType as DefaultFinanceTypeForm;
use App\Services\RequestManager\Activity\DefaultFinanceType as DefaultFinanceTypeRequestManager;
use Illuminate\Http\Request;

/**
 * Class DefaultFinanceTypeController
 * @package App\Http\Controllers\Complete\Activity
 */
class DefaultFinanceTypeController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var DefaultFinanceTypeManager
     */
    protected $defaultFinanceTypeManager;
    /**
     * @var DefaultFinanceTypeForm
     */
    protected $defaultFinanceTypeForm;

    /**
     * @param DefaultFinanceTypeManager $defaultFinanceTypeManager
     * @param DefaultFinanceTypeForm    $defaultFinanceTypeForm
     * @param ActivityManager           $activityManager
     */
    function __construct(DefaultFinanceTypeManager $defaultFinanceTypeManager, DefaultFinanceTypeForm $defaultFinanceTypeForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager           = $activityManager;
        $this->defaultFinanceTypeManager = $defaultFinanceTypeManager;
        $this->defaultFinanceTypeForm    = $defaultFinanceTypeForm;
    }

    /**
     * returns the activity default finance type edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function  index($id)
    {
        $defaultFinanceType = $this->defaultFinanceTypeManager->getDefaultFinanceTypeData($id);
        $activityData       = $this->activityManager->getActivityData($id);
        $form               = $this->defaultFinanceTypeForm->editForm($defaultFinanceType, $id);

        return view('Activity.defaultFinanceType.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * updates activity default finance type
     * @param                                  $id
     * @param Request                          $request
     * @param DefaultFinanceTypeRequestManager $defaultFinanceTypeRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, DefaultFinanceTypeRequestManager $defaultFinanceTypeRequestManager)
    {
        $defaultFinanceType = $request->all();
        $activityData       = $this->activityManager->getActivityData($id);
        if ($this->defaultFinanceTypeManager->update($defaultFinanceType, $activityData)) {
            return redirect()->to(sprintf('/activity/%s', $id))->withMessage('Activity Default Finance Type updated!');
        }

        return redirect()->back();
    }
}
