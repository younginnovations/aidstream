<?php namespace app\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\CountryBudgetItemManager;
use App\Services\FormCreator\Activity\CountryBudgetItem as CountryBudgetItemForm;
use App\Services\RequestManager\Activity\CountryBudgetItem as CountryBudgetItemRequestManager;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Class CountryBudgetItemController
 * @package App\Http\Controllers\Complete\Activity
 */
class CountryBudgetItemController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var CountryBudgetItemForm
     */
    protected $countryBudgetItemForm;
    /**
     * @var CountryBudgetItemManager
     */
    protected $countryBudgetItemManager;

    /**
     * @param CountryBudgetItemManager $countryBudgetItemManager
     * @param CountryBudgetItemForm    $countryBudgetItemForm
     * @param ActivityManager          $activityManager
     */
    public function __construct(
        CountryBudgetItemManager $countryBudgetItemManager,
        CountryBudgetItemForm $countryBudgetItemForm,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->activityManager          = $activityManager;
        $this->countryBudgetItemForm    = $countryBudgetItemForm;
        $this->countryBudgetItemManager = $countryBudgetItemManager;
    }

    /**
     * show country budget item form
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $countryBudgetItem = $this->countryBudgetItemManager->getCountryBudgetItemData($id);
        $form              = $this->countryBudgetItemForm->editForm($countryBudgetItem, $id);

        return view('Activity.countryBudgetItem.edit', compact('form', 'activityData', 'id'));
    }

    /**
     * update country budget item
     * @param                                 $id
     * @param Request                         $request
     * @param CountryBudgetItemRequestManager $countryBudgetItemRequestManager
     * @return mixed
     */
    public function update($id, Request $request, CountryBudgetItemRequestManager $countryBudgetItemRequestManager)
    {
        $activityData = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityData, 'country_budget_items');
        $countryBudgetItems = $request->all();
        foreach ($countryBudgetItems['country_budget_item'] as &$countryBudgetItem) {
            $code                                       = $countryBudgetItem['vocabulary'] == 1 ? 'code_text' : 'code';
            $countryBudgetItem['budget_item'][0][$code] = '';
        }
        if ($this->countryBudgetItemManager->update($countryBudgetItems, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('element.country_budget_items')]]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => trans('element.country_budget_items')]]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
