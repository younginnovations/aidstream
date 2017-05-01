<?php namespace App\Http\Controllers\Lite\Activity;

use App\Http\Requests\Request;
use App\Lite\Services\FormCreator\Activity;
use App\Http\Controllers\Lite\LiteController;
use App\Lite\Services\Activity\ActivityService;
use App\Lite\Services\FormCreator\Budget;
use App\Lite\Services\FormCreator\Transaction;
use App\Lite\Services\Activity\Transaction\TransactionService;
use App\Lite\Services\Validation\ValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/**
 * Class ActivityController
 * @package App\Http\Controllers\Lite\Activity
 */
class ActivityController extends LiteController
{
    /**
     * @var ActivityService
     */
    protected $activityService;

    /**
     * @var Activity
     */
    protected $activityForm;

    /**
     * @var ValidationService
     */
    protected $validation;

    /**
     * Entity type for Activity.
     */
    const ENTITY_TYPE = 'Activity';

    /**
     * @var Budget
     */
    protected $budgetForm;

    /**
     * @var Transaction
     */
    protected $transactionForm;

    /**
     * @var TransactionService
     */
    protected $transactionService;

    /**
     * ActivityController constructor.
     * @param ActivityService    $activityService
     * @param TransactionService $transactionService
     * @param Transaction        $transactionForm
     * @param Budget             $budgetForm
     * @param Activity           $activityForm
     * @param ValidationService  $validationService
     * @internal param Transaction $transaction
     */
    public function __construct(
        ActivityService $activityService,
        TransactionService $transactionService,
        Transaction $transactionForm,
        Budget $budgetForm,
        Activity $activityForm,
        ValidationService $validationService
    ) {
        $this->middleware('auth');
        $this->activityService    = $activityService;
        $this->activityForm       = $activityForm;
        $this->validation         = $validationService;
        $this->budgetForm         = $budgetForm;
        $this->transactionForm    = $transactionForm;
        $this->transactionService = $transactionService;
    }

    /**
     * Show the list of activities for the current Organization.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $organisation  = auth()->user()->organization;
        $orgId         = $organisation->id;
        $orgIdentifier = getVal((array) $organisation->reporting_org, [0, 'reporting_organization_identifier']);

        if (Gate::denies('belongsToOrganization', $organisation)) {
            return redirect()->route('lite.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $activities              = $this->activityService->all();
        $stats                   = $this->activityService->getActivityStats();
        $noOfPublishedActivities = $this->activityService->getNumberOfPublishedActivities($orgId);
        $lastPublishedToIATI     = $this->activityService->lastPublishedToIATI($orgId);

        return view('lite.activity.index', compact('activities', 'form', 'stats', 'noOfPublishedActivities', 'lastPublishedToIATI', 'orgIdentifier'));
    }

    /**
     * Displays form to create activity.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $organisation = auth()->user()->organization;
        $settings     = $organisation->settings;
        $version      = session('version');

        if (Gate::denies('belongsToOrganization', $organisation)) {
            return redirect()->route('lite.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $organisation);

        $data           = ['organisation' => $organisation->toArray(), 'settings' => $settings->toArray()];
        $countryDetails = file_get_contents(public_path('/data/countriesDetails.json'));

        if (!$this->validation->passes($data, 'ActivityRequiredFields', $version)) {
            return redirect()->route('lite.settings.edit')->withResponse(['type' => 'danger', 'code' => ['settings_incomplete']]);
        }

        $form = $this->activityForm->form(route('lite.activity.store'), trans('lite/elementForm.add_this_activity'));

        return view('lite.activity.create', compact('form', 'countryDetails'));
    }

    /**
     * Save Activity to the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rawData      = $request->except('_token');
        $version      = session('version');
        $organisation = auth()->user()->organization;

        if (Gate::denies('belongsToOrganization', $organisation)) {
            return redirect()->route('lite.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $organisation);

        if (!$this->validation->passes($rawData, self::ENTITY_TYPE, $version)) {
            return redirect()->back()->with('errors', $this->validation->errors())->withInput($rawData);
        }

        if (!($activity = $this->activityService->store($rawData, $version))) {
            return redirect()->route('lite.activity.index')->withResponse(['type' => 'danger', 'code' => ['save_failed', ['name' => trans('lite/global.activity')]]]);
        }

        return redirect()->route('lite.activity.show', [$activity->id])->withResponse(['type' => 'success', 'code' => ['created', ['name' => trans('lite/global.activity')]]]);
    }

    /**
     * Display the detail of an activity.
     *
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($activityId)
    {
        $activity = $this->activityService->find($activityId);
        $count    = [];

        if (Gate::denies('ownership', $activity)) {
            return redirect()->route('lite.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $version          = session('version');
        $documentLinks    = $this->activityService->documentLinks($activityId, $version);
        $transaction      = $activity->transactions->toArray();
        $transactions     = $this->transactionService->getFilteredTransactions($transaction);
        $location         = $this->activityService->location($activity->toArray());
        $disbursement     = getVal($transactions, ['disbursement'], '');
        $expenditure      = getVal($transactions, ['expenditure'], '');
        $incoming         = getVal($transactions, ['incoming'], '');
        $defaultCurrency  = $this->transactionService->getDefaultCurrency($activity);
        $statusLabel      = ['draft', 'completed', 'verified', 'published'];
        $activityWorkflow = $activity->activity_workflow;
        $btn_status_label = ['Completed', 'Verified', 'Published'];
        $btn_text         = $activityWorkflow > 2 ? "" : $btn_status_label[$activityWorkflow];

        if ($activity['activity_workflow'] == 0) {
            $nextRoute = route('lite.activity.complete', $activityId);
        } elseif ($activity['activity_workflow'] == 1) {
            $nextRoute = route('lite.activity.verify', $activityId);
        } else {
            $nextRoute = route('lite.activity.publish', $activityId);
        }

        $count['budget'] = ($activity->budget ? count($activity->budget) : 0);
//        $count['transaction'] = ($disbursement ? count($disbursement) : 0) + ($expenditure ? count($expenditure) : 0) + ($incoming ? count($incoming) : 0);
        $count['disbursement']   = $disbursement ? count($disbursement) : 0;
        $count['expenditure']    = $expenditure ? count($expenditure) : 0;
        $count['incoming_funds'] = $incoming ? count($incoming) : 0;

        return view(
            'lite.activity.show',
            compact('activity', 'statusLabel', 'activityWorkflow', 'btn_text', 'nextRoute', 'disbursement', 'expenditure', 'incoming', 'defaultCurrency', 'documentLinks', 'count', 'location')
        );
    }

    /**
     * Return form to edit an activity.
     *
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($activityId)
    {
        $version       = session('version');
        $activityModel = $this->activityService->find($activityId);
        $activity      = $this->activityService->edit($activityId, $version);

        if (Gate::denies('ownership', $activityModel)) {
            return redirect()->route('lite.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }
        $this->authorize('edit_activity', $activityModel);

        $countryDetails = file_get_contents(public_path('/data/countriesDetails.json'));
        $form           = $this->activityForm->form(route('lite.activity.update', $activityId), trans('lite/elementForm.update_this_activity'), $activity);

        return view('lite.activity.create', compact('form', 'activity', 'countryDetails', 'activityId'));
    }


    /**
     * Delete an activity
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request)
    {
        $activityId = $request->get('index');

        $activity = $this->activityService->find($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('delete_activity', $activity);

        if ($this->activityService->delete($activityId)) {
            return redirect()->back()->withResponse(['type' => 'success', 'code' => ['deleted', ['name' => trans('lite/global.activity')]]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'code' => ['deleted_failed', ['name' => trans('lite/global.activity')]]]);
    }

    /**
     * Update an activity
     *
     * @param         $activityId
     * @param Request $request
     * @return RedirectResponse
     */
    public function update($activityId, Request $request)
    {
        $activity = $this->activityService->find($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }
        $this->authorize('edit_activity', $activity);

        $rawData = $request->except('_token');
        $version = session('version');
//    dd($rawData);
        if (!$this->validation->passes($rawData, self::ENTITY_TYPE, $version)) {
            return redirect()->back()->with('errors', $this->validation->errors())->withInput($rawData);
        }

        if (!$this->activityService->update($activityId, $rawData, $version)) {
            return redirect()->route('lite.activity.show', $activityId)->withResponse(['type' => 'danger', 'code' => ['save_failed', ['name' => trans('lite/global.activity')]]]);
        }

        return redirect()->route('lite.activity.show', $activityId)->withResponse(['type' => 'success', 'code' => ['updated', ['name' => trans('lite/global.activity')]]]);
    }

    /**
     * Duplicate an activity
     *
     * @param Request $request
     * @return RedirectResponse
     * @internal param $activityId
     */
    public function duplicate(Request $request)
    {
        $activityId                                   = $request->get('activityId');
        $activityIdentifier                           = $request->get('activityIdentifier');
        $rawData                                      = $this->activityService->find($activityId)->toArray();
        $rawData['identifier']['activity_identifier'] = $activityIdentifier;

        $version      = session('version');
        $organisation = auth()->user()->organization;

        $reverseMapped = $this->activityService->reverseTransform($rawData, $version);

        if (Gate::denies('belongsToOrganization', $organisation)) {
            return redirect()->route('lite.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $organisation);

        if (!$this->validation->passes($reverseMapped, self::ENTITY_TYPE, $version)) {
            return redirect()->back()->withResponse(['messages' => ['Your data is not properly validated.'], 'type' => 'error'])->withInput($rawData);
        }

        if (!($activity = $this->activityService->store($reverseMapped, $version))) {
            return redirect()->route('lite.activity.index')->withResponse(['type' => 'danger', 'code' => ['save_failed', ['name' => trans('lite/global.activity')]]]);
        }

        return redirect()->route('lite.activity.show', [$activity->id])->withResponse(['type' => 'success', 'code' => ['created', ['name' => trans('lite/global.activity')]]]);
    }

    /**
     * Provides duplicate form
     *
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createDuplicate($activityId)
    {
        $model = ['activityId' => $activityId];

        $form = $this->activityForm->duplicateForm(route('lite.activity.duplicate'), trans('lite/global.create_activity'), $model);

        return view('lite.activity.duplicate', compact('form', 'activityId'));
    }

    /**
     * Creates budget of an activity.
     *
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createBudget($activityId)
    {
        $activity = $this->activityService->find($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $activity);

        $form = $this->budgetForm->form(route('lite.activity.budget.store', $activityId));

        return view('lite.activity.budget.edit', compact('form'));
    }

    /**
     * Edits budget of an activity.
     *
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editBudget($activityId)
    {
        $activity = $this->activityService->find($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $activity);

        $version = session('version');
        $model   = $this->activityService->getBudgetModel($activityId, $version);
        $form    = $this->budgetForm->form(route('lite.activity.budget.update', $activityId), $model);

        return view('lite.activity.budget.edit', compact('form'));
    }

    /**
     * Stores Budget of an activity.
     *
     * @param         $activityId
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeBudget($activityId, Request $request)
    {
        $activity = $this->activityService->find($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }
        $this->authorize('add_activity', $activity);

        $rawData = $request->except('_token');
        $version = session('version');

        if (!$this->validation->passes($rawData, 'Budget', $version)) {
            return redirect()->back()->with('errors', $this->validation->errors())->withInput($rawData);
        }

        if ($this->activityService->addBudget($activityId, $rawData, $version)) {
            return redirect()->route('lite.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.budget_success_created')]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'messages' => [trans('error.error_budget_create')]]);
    }

    /**
     * Stores Budget of an activity.
     *
     * @param         $activityId
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateBudget($activityId, Request $request)
    {
        $activity = $this->activityService->find($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $activity);

        $rawData = $request->except('_token');
        $version = session('version');

        if (!$this->validation->passes($rawData, 'Budget', $version)) {
            return redirect()->back()->with('errors', $this->validation->errors())->withInput($rawData);
        }

        if ($this->activityService->updateBudget($activityId, $rawData, $version)) {
            return redirect()->route('lite.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.budget_success_created')]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'messages' => [trans('error.error_budget_create')]]);
    }

    /**
     * Deletes a single Budget.
     *
     * @param         $activityId
     * @param Request $request
     * @return mixed
     */
    public function deleteBudget($activityId, Request $request)
    {
        $activity = $this->activityService->find($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('delete_activity', $activity);

        if ($this->activityService->deleteBudget($activityId, $request)) {
            return redirect()->route('lite.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.budget_success_deleted')]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'messages' => [trans('error.error_budget_create')]]);
    }

    /**
     * Creates budget of an activity.
     *
     * @param $activityId
     * @param $typeCode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param $type
     */
    public function createTransaction($activityId, $typeCode)
    {
        $activity = $this->activityService->find($activityId);
        $ids      = [];

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $activity);

        if ($typeCode == 3 || $typeCode == 4 || $typeCode == 1) {
            $type = $this->transactionService->getTransactionType($typeCode);
            $form = $this->transactionForm->form(route('lite.activity.transaction.store', [$activityId, $type]), $type);

            return view('lite.activity.transaction.edit', compact('form', 'type', 'ids'));
        }

        return redirect()->route('lite.activity.show', $activityId)->withResponse(['type' => 'warning', 'messages' => [trans('error.404_not_found')]]);

    }

    /**
     * Edits transaction of an activity.
     *
     * @param $activityId
     * @param $transactionType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param $type
     */
    public function editTransaction($activityId, $transactionType)
    {
        $activity = $this->activityService->find($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }
        $this->authorize('edit_activity', $activity);
        $type = $this->transactionService->getTransactionType($transactionType);

        if ($type == 'Disbursement' || $type == 'Expenditure' || $type == 'IncomingFunds') {
            $version = session('version');
            $model   = $this->transactionService->getModel($activityId, $transactionType, $version);

            $newModel[strtolower($type)] = $model;

            $ids = $this->transactionService->getIds($model);

            $form = $this->transactionForm->form(route('lite.activity.transaction.update', [$activityId, $transactionType]), $type, $newModel);

            return view('lite.activity.transaction.edit', compact('form', 'type', 'ids'));
        }

        return redirect()->route('lite.activity.show', $activityId)->withResponse(['type' => 'warning', 'messages' => [trans('error.404_not_found')]]);
    }

    /**
     * Updates Transaction for current Activity
     *
     * @param         $activityId
     * @param         $type
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateTransaction($activityId, $type, Request $request)
    {
        $rawData  = $request->except(['_token', 'ids']);
        $version  = session('version');
        $ids      = $request->get('ids');
        $activity = $this->activityService->find($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $activity);

        if (!$this->validation->passes($rawData, 'Transaction', $version)) {
            return redirect()->back()->with('errors', $this->validation->errors())->withInput($rawData);
        }

        $this->transactionService->updateTransaction($activityId, $ids, $rawData);

        if ($this->transactionService->updateOrCreate($activityId, $type, $rawData, $version)) {
            return redirect()->route('lite.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.transaction_success_updated')]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'messages' => [trans('error.error_transaction_update')]]);
    }

    /**
     * Stores Transaction of an activity.
     *
     * @param         $activityId
     * @param         $type
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeTransaction($activityId, $type, Request $request)
    {
        $rawData = $request->except('_token');
        $version = session('version');
        $method  = sprintf('add%s', ucfirst($type));

        $activity = $this->activityService->find($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $activity);

        if (!$this->validation->passes($rawData, 'Transaction', $version)) {
            return redirect()->back()->with('errors', $this->validation->errors())->withInput($rawData);
        }

        if ($this->transactionService->$method($activityId, $rawData, $version)) {
            return redirect()->route('lite.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.transaction_success_created')]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'messages' => [trans('error.error_transaction_create')]]);
    }

    /**
     * Deletes a single Transaction.
     *
     * @param         $activityId
     * @param Request $request
     * @return mixed
     */
    public function deleteTransaction($activityId, Request $request)
    {
        $activity = $this->activityService->find($activityId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('delete_activity', $activity);

        $transactionId = $request->get('index');

        if ($this->transactionService->delete($activityId, $transactionId)) {
            return redirect()->route('lite.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.transaction_success_deleted')]]);
        }

        return redirect()->back()->withResponse(['type' => 'danger', 'messages' => [trans('error.error_transaction_delete')]]);
    }

    /**
     * Returns budget details of all activities through AJAX Request.
     *
     * @return array
     */
    public function budgetDetails()
    {
        return $this->activityService->getBudgetDetails();
    }
}

