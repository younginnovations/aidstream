<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\CapitalSpendManager;
use App\Services\FormCreator\Activity\CapitalSpend as CapitalSpendForm;
use App\Services\RequestManager\Activity\CapitalSpend as CapitalSpendRequestManager;
use Illuminate\Http\Request;

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
        $capitalSpend = $request->all();
        $activityData = $this->activityManager->getActivityData($id);
        if ($this->capitalSpendManager->update($capitalSpend, $activityData)) {
            return redirect()->to(sprintf('/activity/%s', $id))->withMessage('Activity Capital Spend updated!');
        }

        return redirect()->back();
    }
}
