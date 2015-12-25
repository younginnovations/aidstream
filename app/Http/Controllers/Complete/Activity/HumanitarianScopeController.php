<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\HumanitarianScopeManager;
use App\Services\FormCreator\Activity\HumanitarianScope;
use App\Services\RequestManager\Activity\HumanitarianScope as HumanitarianScopeRequest;
use Illuminate\Http\Request;

/**
 * Class HumanitarianScopeController
 * @package App\Http\Controllers\Complete\Activity
 */
class HumanitarianScopeController extends Controller
{
    /**
     * @var HumanitarianScope
     */
    protected $humanitarianScopeForm;
    /**
     * @var HumanitarianScopeManager
     */
    protected $humanitarianScopeManager;

    /**
     * @param HumanitarianScopeManager $humanitarianScopeManager
     * @param HumanitarianScope        $humanitarianScopeForm
     * @param ActivityManager          $activityManager
     */
    function __construct(HumanitarianScopeManager $humanitarianScopeManager, HumanitarianScope $humanitarianScopeForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager          = $activityManager;
        $this->humanitarianScopeForm    = $humanitarianScopeForm;
        $this->humanitarianScopeManager = $humanitarianScopeManager;
    }

    /**
     * view form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function  index($id)
    {
        $countryBudgetItem = $this->humanitarianScopeManager->getActivityHumanitarianScopeData($id);
        $activityData      = $this->activityManager->getActivityData($id);
        $form              = $this->humanitarianScopeForm->editForm($countryBudgetItem, $id);

        return view('Activity.humanitarianScope.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * update humanitarian scope
     * @param                          $id
     * @param Request                  $request
     * @param HumanitarianScopeRequest $humanitarianScopeRequest
     * @return mixed
     */
    public function update($id, Request $request, HumanitarianScopeRequest $humanitarianScopeRequest)
    {
        $this->authorize(['edit_activity', 'add_activity']);
        $humanitarianScope = $request->all();
        $activityData      = $this->activityManager->getActivityData($id);
        if ($this->humanitarianScopeManager->update($humanitarianScope, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Humanitarian Scope']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Humanitarian Scope']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
