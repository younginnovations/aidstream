<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\BudgetManager;
use App\Services\FormCreator\Activity\Budget as BudgetForm;
use App\Services\Activity\ActivityManager;
use App\Http\Requests\Request;
use App\Services\RequestManager\Activity\Budget as BudgetRequestManager;

/**
 * Class BudgetController
 * @package App\Http\Controllers\Complete\Activity
 */
class BudgetController extends Controller
{

    /**
     * @param BudgetManager   $budgetManager
     * @param BudgetForm      $budgetForm
     * @param ActivityManager $activityManager
     */
    function __construct(BudgetManager $budgetManager, BudgetForm $budgetForm, ActivityManager $activityManager)
    {
        $this->middleware('auth');
        $this->budgetManager   = $budgetManager;
        $this->budgetForm      = $budgetForm;
        $this->activityManager = $activityManager;
    }

    public function index($id)
    {
        $budget       = $this->budgetManager->getbudgetData($id);
        $activityData = $this->activityManager->getActivityData($id);
        $form         = $this->budgetForm->editForm($budget, $id);

        return view('Activity.budget.edit', compact('form', 'activityData', 'id'));
    }

    public function update($id, Request $request, BudgetRequestManager $budgetRequestManager)
    {
        $this->authorize(['edit_activity', 'add_activity']);
        $budget       = $request->all();
        $activityData = $this->activityManager->getActivityData($id);
        if ($this->budgetManager->update($budget, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Budget']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Budget']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
