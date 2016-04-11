<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\CapitalSpendManager;
use App\Services\FormCreator\Activity\CapitalSpend as CapitalSpendForm;
use App\Services\RequestManager\Activity\CapitalSpend as CapitalSpendRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class CapitalSpendController
 * @package App\Http\Controllers\Complete\Activity
 */
class CapitalSpendController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var CapitalSpendManager
     */
    protected $capitalSpendManager;
    /**
     * @var CapitalSpendForm
     */
    protected $capitalSpendForm;

    /**
     * @param CapitalSpendManager $capitalSpendManager
     * @param CapitalSpendForm    $capitalSpendForm
     * @param ActivityManager     $activityManager
     */
    function __construct(CapitalSpendManager $capitalSpendManager, CapitalSpendForm $capitalSpendForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->activityManager     = $activityManager;
        $this->capitalSpendManager = $capitalSpendManager;
        $this->capitalSpendForm    = $capitalSpendForm;
    }

    /**
     * returns the Activity Capital Spend edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function  index($id)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $capitalSpend = $this->capitalSpendManager->getCapitalSpendData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->capitalSpendForm->editForm($capitalSpend, $id);

        return view('Activity.capitalSpend.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * updates Activity Capital Spend
     * @param                                 $id
     * @param Request                         $request
     * @param CapitalSpendRequestManager      $capitalSpendRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, CapitalSpendRequestManager $capitalSpendRequestManager)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $activityData = $this->activityManager->getActivityData($id);
        $this->authorizeByRequestType($activityData, 'capital_spend');
        $capitalSpend = $request->all();
        if ($this->capitalSpendManager->update($capitalSpend, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Capital Spend']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Capital Spend']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
