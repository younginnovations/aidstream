<?php namespace App\Http\Controllers\Np\Activity;

use App\Np\Services\FormCreator\NpActivity;
use App\Http\Controllers\Lite\LiteController;
use App\Np\Services\Activity\ActivityService;
use App\Np\Services\FormCreator\Budget;
use App\Np\Services\FormCreator\Transaction;
use App\Models\Settings;
use App\Np\Services\Activity\Transaction\TransactionService;
use App\Services\Activity\ActivityManager;
use App\Np\Services\Traits\GeocodeReverser;
use App\Np\Services\Validation\ValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\SettingsManager;
use Illuminate\Session\SessionManager;

/**
 * Class ActivityController
 * @package App\Http\Controllers\Np\Activity
 */
class ActivityController extends LiteController
{
    use GeocodeReverser;
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
     * @var Settings
     */
    protected $settings;

    /**
     * Entity type for Activity.
     */
    const ENTITY_TYPE = 'Activity';

    /**
     * @var Budget
     */
    protected $budgetForm;

    /**
     * Organization Id
     *
     * @var Integer
     */
    protected $organization_id;

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
        SettingsManager $settingsManager,
        SessionManager $sessionManager,
        TransactionService $transactionService,
        Transaction $transactionForm,
        ActivityManager $activityManager,
        Settings $settings,
        Budget $budgetForm,
        NpActivity $activityForm,
        ValidationService $validationService
    ) {
        $this->middleware('auth');
        $this->settingsManager    = $settingsManager;
        $this->sessionManager     = $sessionManager;
        $this->activityService    = $activityService;
        $this->activityForm       = $activityForm;
        $this->validation         = $validationService;
        $this->activityManager    = $activityManager;
        $this->budgetForm         = $budgetForm;
        $this->transactionForm    = $transactionForm;
        $this->settings           = $settings;
        $this->transactionService = $transactionService;
        $this->organization_id    = $this->sessionManager->get('org_id');
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
            return redirect()->route('np.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $activities              = $this->activityService->all();
        $stats                   = $this->activityService->getActivityStats();
        $noOfPublishedActivities = $this->activityService->getNumberOfPublishedActivities($orgId);
        $lastPublishedToIATI     = $this->activityService->lastPublishedToIATI($orgId);

        return view('np.activity.index', compact('activities', 'form', 'stats', 'noOfPublishedActivities', 'lastPublishedToIATI', 'orgIdentifier'));
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

        $municipalitiesArray = collect(\DB::table('municipalities')->get());
        $municipalities = [];
        $municipalities = $municipalitiesArray->map(function ($municipality) {
            return [
                "id" => $municipality->id,
                "text" => $municipality->name
            ];
        });

        $wardsArray = collect(\DB::table('municipalities')->select('wards', 'id', 'name')->get());
        $wards =[];
        $wards = $wardsArray->map(function ($ward) {
            $map = [];
            for ($i = 1; $i <= $ward->wards; $i++) {
                $map[] = array("id" => $i, "text" => $i);
            }
            return [
                "text" => $ward->name,
                "id" => $ward->id,
                "children" => $map,
            ];
        });
        $wards = json_encode($wards->toArray());
        $municipalities = json_encode($municipalities->toArray());
        $locationArray = "";

        if (Gate::denies('belongsToOrganization', $organisation)) {
            return redirect()->route('np.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $organisation);

        $data           = ['organisation' => $organisation->toArray(), 'settings' => $settings->toArray()];
        $countryDetails = file_get_contents(public_path('/data/countriesDetails.json'));
        $geoJson        = file_get_contents(public_path('/data/countries.geo.json'));

        if (!$this->validation->passes($data, 'ActivityRequiredFields', $version)) {
            return redirect()->route('np.settings.edit')->withResponse(['type' => 'danger', 'code' => ['settings_incomplete']]);
        }

        $form = $this->activityForm->form(route('np.activity.store'), trans('lite/elementForm.add_this_activity'));

        return view('np.activity.create', compact('form', 'countryDetails', 'geoJson', 'wards', 'municipalities','locationArray'));
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
            return redirect()->route('np.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }
        $this->authorize('add_activity', $organisation);

        if (!$this->validation->passes($rawData, self::ENTITY_TYPE, $version)) {
            return redirect()->back()->with('errors', $this->validation->errors())->withInput($rawData);
        }

        if (!$this->activityService->checkError($rawData)) {
            return redirect()->back()->withResponse(['type' => 'danger', 'code' => ['municipality_required', ['name' => trans('lite/global.activity')]]])->withInput($rawData);
        }

        if (!($activity = $this->activityService->store($rawData, $version))) {
            return redirect()->route('np.activity.index')->withResponse(['type' => 'danger', 'code' => ['save_failed', ['name' => trans('lite/global.activity')]]]);
        }
        $this->activityService->saveLocation($rawData, $activity->id);

        return redirect()->route('np.activity.show', [$activity->id])->withResponse(['type' => 'success', 'code' => ['created', ['name' => trans('lite/global.activity')]]]);
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
        $locationArray = collect(\DB::table('activity_location')
                    ->leftjoin('municipalities', 'activity_location.municipality_id', '=', 'municipalities.id')
                    ->select('name', 'ward')
                    ->where('activity_id', '=', $activityId)
                    ->get());

        $locationArray = $locationArray->groupBy('name')
                            ->map(function($location){
                                $wards = $location->map(function($ward){
                                    return $ward->ward;
                             });
                        
            return $wards->unique()->sort();
        });
        $locationArray = $locationArray->toArray();

        if (Gate::denies('ownership', $activity)) {
            return redirect()->route('np.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $version            = session('version');
        $documentLinks      = $this->activityService->documentLinks($activityId, $version);
        $transaction        = $activity->transactions->toArray();
        $transactions       = $this->transactionService->getFilteredTransactions($transaction);
        $location           = $this->activityService->location($activity->toArray());
        $disbursement       = getVal($transactions, ['disbursement'], '');
        $expenditure        = getVal($transactions, ['expenditure'], '');
        $incoming           = getVal($transactions, ['incoming'], '');
        $defaultCurrency    = $this->transactionService->getDefaultCurrency($activity);
        $statusLabel        = ['draft', 'completed', 'verified', 'published'];
        $activityWorkflow   = $activity->activity_workflow;
        $btn_status_label   = ['Completed', 'Verified', 'Published'];
        $btn_text           = $activityWorkflow > 2 ? "" : $btn_status_label[$activityWorkflow];
        $recipientCountries = $this->activityService->getRecipientCountry($activity->recipient_country);

        if ($activity->activity_workflow == 3) {
            $filename                = $this->getPublishedActivityFilename($this->organization_id, $activity);
            $activityPublishedStatus = $this->getPublishedActivityStatus($filename, $this->organization_id);
            $message                 = $this->getMessageForPublishedActivity($activityPublishedStatus, $filename, $activity->organization);
        }

        if ($activity['activity_workflow'] == 0) {
            $nextRoute = route('np.activity.complete', $activityId);
        } elseif ($activity['activity_workflow'] == 1) {
            $nextRoute = route('np.activity.verify', $activityId);
        } else {
            $nextRoute = route('np.activity.publish', $activityId);
        }

        return view(
            'np.activity.show',
            compact(
                'activity',
                'statusLabel',
                'activityWorkflow',
                'btn_text',
                'nextRoute',
                'disbursement',
                'expenditure',
                'incoming',
                'defaultCurrency',
                'activityPublishedStatus',
                'documentLinks',
                'location',
                'recipientCountries',
                'locationArray'
            )
        );
    }

    /** Returns the filename that is generated when activity is published based on publishing type.
     * @param $organization_id
     * @param $activity
     * @return string
     */
    public function getPublishedActivityFilename($organization_id, $activity)
    {
        $settings       = $this->settings->where('organization_id', $organization_id)->first();
        $publisherId    = $settings->registry_info[0]['publisher_id'];
        $publishingType = $settings->publishing_type;

        if ($publishingType != "segmented") {
            $endName = 'activities';
        } else {
            $activityElement = $this->activityManager->getActivityElement();
            $xmlService      = $activityElement->getActivityXmlService();
            $endName         = $xmlService->segmentedXmlFile($activity);
        }
        $filename = sprintf('%s' . '-' . '%s.xml', $publisherId, $endName);

        return $filename;
    }

    /** Returns according to published to registry status of the activity.
     * @param $filename
     * @param $organization_id
     * @return string
     */
    public function getPublishedActivityStatus($filename, $organization_id)
    {
        $activityPublished   = $this->activityManager->getActivityPublishedData($filename, $organization_id);
        $settings            = $this->settings->where('organization_id', $organization_id)->first();
        $autoPublishSettings = $settings->registry_info[0]['publish_files'];
        $status              = 'Unlinked';

        if ($activityPublished) {
            if ($autoPublishSettings == "no") {
                ($activityPublished->published_to_register == 0) ? $status = "Unlinked" : $status = "Linked";
            } else {
                ($activityPublished->published_to_register == 0) ? $status = "unlinked" : $status = "Linked";
            }
        }

        return $status;
    }

    /** Returns message according to the status of the activity
     * @param $status
     * @param $filename
     * @return string
     */
    protected function getMessageForPublishedActivity($status, $filename, $organization)
    {
        $publisherId = getVal($organization->settings->toArray(), ['registry_info', 0, 'publisher_id'], null);
        $link        = $publisherId ? "<a href='https://iatiregistry.org/publisher/" . $publisherId . "' target='_blank'>IATI registry</a>" : "IATI Registry";

        if ($status == "Unlinked") {
            $message = trans('error.activity_not_published_to_registry');
        } elseif ($status == "Linked") {
            $message = trans('success.activity_published_to_registry', ['link' => $link]) . ' ' . "<a href='/files/xml/$filename'>$filename</a>";
        } else {
            $message = trans('error.republish_activity');
        }

        return $message;
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
        $municipalities = collect(\DB::table('activity_location')->where('activity_id','=',$activityId)->distinct()->get(['municipality_id']));

        $db = \DB::Class;
        $locationArray = $municipalities->map(function($location) use ($activityId, $db){
            $wards = collect(\DB::table('activity_location')->where('activity_id','=',$activityId)->where('municipality_id','=',$location->municipality_id)->get(['ward']));
            $wards = $wards->map(function($ward){
                return $ward->ward;
            });
            return [
                "municipality" => $location->municipality_id,
                "wards"        => $wards->toArray()
            ];
        });

        foreach($locationArray as $key=>$activityLocation){
            $activity["location"][$key]['municipality'] = $activityLocation['municipality'];
            $activity["location"][$key]['wards']        = $activityLocation['wards'];
        }

        if (Gate::denies('ownership', $activityModel)) {
            return redirect()->route('np.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }
        $municipalitiesArray = collect(\DB::table('municipalities')->get());
        $municipalities = [];
        $municipalities = $municipalitiesArray->map(function ($mun) {
            return [
                "id" => $mun->id,
                "text" => $mun->name
            ];
        });

        $wardsArray = collect(\DB::table('municipalities')->select('wards', 'id', 'name')->get());
        $wards =[];
        $wards = $wardsArray->map(function ($ward) {
            $map = [];
            for ($i = 1; $i <= $ward->wards; $i++) {
                $map[] = array("id" => $i, "text" => $i);
            }
            return [
                "text" => $ward->name,
                "id" => $ward->id,
                "children" => $map,
            ];
        });
        $wards = json_encode($wards->toArray());
        $municipalities = json_encode($municipalities->toArray());
        $locationArray = json_encode($locationArray->toArray());

        $this->authorize('edit_activity', $activityModel);

        $countryDetails = file_get_contents(public_path('/data/countriesDetails.json'));
        $geoJson        = file_get_contents(public_path('/data/countries.geo.json'));
        $form           = $this->activityForm->form(route('np.activity.update', $activityId), trans('lite/elementForm.update_this_activity'), $activity);

        return view('np.activity.create', compact('form', 'activity', 'countryDetails', 'geoJson', 'activityId', 'wards', 'municipalities', 'locationArray'));
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

        if (!$this->validation->passes($rawData, self::ENTITY_TYPE, $version)) {
            return redirect()->back()->with('errors', $this->validation->errors())->withInput($rawData);
        }

        if (!$this->activityService->update($activityId, $rawData, $version)) {
            return redirect()->route('np.activity.show', $activityId)->withResponse(['type' => 'danger', 'code' => ['save_failed', ['name' => trans('lite/global.activity')]]]);
        }
        $this->activityService->saveLocation($rawData, $activityId);

        return redirect()->route('np.activity.show', $activityId)->withResponse(['type' => 'success', 'code' => ['updated', ['name' => trans('lite/global.activity')]]]);
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
            return redirect()->route('np.activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $organisation);

        if (!$this->validation->passes($reverseMapped, self::ENTITY_TYPE, $version)) {
            return redirect()->back()->withResponse(['messages' => ['Your data is not properly validated.'], 'type' => 'error'])->withInput($rawData);
        }

        if (!($activity = $this->activityService->store($reverseMapped, $version))) {
            return redirect()->route('np.activity.index')->withResponse(['type' => 'danger', 'code' => ['save_failed', ['name' => trans('lite/global.activity')]]]);
        }

        return redirect()->route('np.activity.show', [$activity->id])->withResponse(['type' => 'success', 'code' => ['created', ['name' => trans('lite/global.activity')]]]);
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

        $form = $this->activityForm->duplicateForm(route('np.activity.duplicate'), trans('lite/global.create_activity'), $model);

        return view('np.activity.duplicate', compact('form', 'activityId'));
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

        $form = $this->budgetForm->form(route('np.activity.budget.store', $activityId));

        return view('np.activity.budget.edit', compact('form'));
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
        $form    = $this->budgetForm->form(route('np.activity.budget.update', $activityId), $model);

        return view('np.activity.budget.edit', compact('form'));
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
            return redirect()->route('np.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.budget_success_created')]]);
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
            return redirect()->route('np.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.budget_success_created')]]);
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
            return redirect()->route('np.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.budget_success_deleted')]]);
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
            $form = $this->transactionForm->form(route('np.activity.transaction.store', [$activityId, $type]), $type);

            return view('np.activity.transaction.edit', compact('form', 'type', 'ids'));
        }

        return redirect()->route('np.activity.show', $activityId)->withResponse(['type' => 'warning', 'messages' => [trans('error.404_not_found')]]);
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

            $form = $this->transactionForm->form(route('np.activity.transaction.update', [$activityId, $transactionType]), $type, $newModel);

            return view('np.activity.transaction.edit', compact('form', 'type', 'ids'));
        }

        return redirect()->route('np.activity.show', $activityId)->withResponse(['type' => 'warning', 'messages' => [trans('error.404_not_found')]]);
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
            return redirect()->route('np.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.transaction_success_updated')]]);
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
            return redirect()->route('np.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.transaction_success_created')]]);
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
            return redirect()->route('np.activity.show', $activityId)->withResponse(['type' => 'success', 'messages' => [trans('success.transaction_success_deleted')]]);
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function reverseGeoCode(Request $request)
    {
        $latitude  = $request->get('latitude');
        $longitude = $request->get('longitude');

        return response($this->reverse($latitude, $longitude));
    }
}
