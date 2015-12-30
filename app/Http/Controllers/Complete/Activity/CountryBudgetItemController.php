<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\CountryBudgetItemManager;
use App\Services\FormCreator\Activity\CountryBudgetItem as CountryBudgetItemForm;
use App\Services\RequestManager\Activity\CountryBudgetItem as CountryBudgetItemRequestManager;
use App\Http\Requests\Request;

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
    function __construct(
        CountryBudgetItemManager $countryBudgetItemManager,
        CountryBudgetItemForm $countryBudgetItemForm,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->activityManager          = $activityManager;
        $this->countryBudgetItemForm    = $countryBudgetItemForm;
        $this->countryBudgetItemManager = $countryBudgetItemManager;
    }

    public function  index($id)
    {
        $countryBudgetItem = $this->countryBudgetItemManager->getCountryBudgetItemData($id);
        $activityData      = $this->activityManager->getActivityData($id);
        $form              = $this->countryBudgetItemForm->editForm($countryBudgetItem, $id);

        return view('Activity.countryBudgetItem.edit', compact('form', 'activityData', 'id'));
    }

    public function update($id, Request $request, CountryBudgetItemRequestManager $countryBudgetItemRequestManager)
    {
        $this->authorize(['edit_activity', 'add_activity']);
        $countryBudgetItem = $request->all();
        $activityData      = $this->activityManager->getActivityData($id);
        if ($this->countryBudgetItemManager->update($countryBudgetItem, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Country Budget Item']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Country Budget Item']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
