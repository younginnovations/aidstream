<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\DefaultFinanceTypeManager;
use App\Services\FormCreator\Activity\DefaultFinanceType as DefaultFinanceTypeForm;
use App\Services\RequestManager\Activity\DefaultFinanceType as DefaultFinanceTypeRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

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
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $defaultFinanceType = $this->defaultFinanceTypeManager->getDefaultFinanceTypeData($id);
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
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityData, 'default_finance_type');
        $defaultFinanceType = $request->all();
        if ($this->defaultFinanceTypeManager->update($defaultFinanceType, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Default Finance Type']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Default Finance Type']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
