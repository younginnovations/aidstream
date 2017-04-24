<?php namespace App\Http\Controllers\Complete\Activity\Import\Transaction;


use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\Activity\ActivityManager;
use App\Services\CsvImporter\ImportTransactionManager;
use App\Services\FormCreator\Activity\UploadTransaction;
use App\Core\V201\Requests\Activity\UploadTransaction as UploadTransactionRequest;
use Illuminate\Support\Facades\Gate;

/**
 * Class ImportTransactionController
 * @package App\Http\Controllers\Complete\Activity\Import\Transaction
 */
class ImportTransactionController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * @var UploadTransaction
     */
    protected $transactionUploadForm;
    /**
     * @var ImportTransactionManager
     */
    protected $transactionManager;

    /**
     * @param ActivityManager          $activityManager
     * @param UploadTransaction        $transactionUploadForm
     * @param ImportTransactionManager $transactionManager
     */
    function __construct(ActivityManager $activityManager, UploadTransaction $transactionUploadForm, ImportTransactionManager $transactionManager)
    {
        $this->middleware('auth');
        $this->activityManager       = $activityManager;
        $this->transactionUploadForm = $transactionUploadForm;
        $this->transactionManager    = $transactionManager;
    }

    /**
     * Display transaction uploader form.
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $form = $this->transactionUploadForm->createForm($id);

        return view('Activity.transaction.uploader', compact('form', 'activity', 'id'));
    }

    /**
     * Store uploaded csv in temp storage.
     * Trigger Csv Uploaded Event.
     *
     * @param                          $id
     * @param UploadTransactionRequest $request
     * @return mixed
     */
    public function store($id, UploadTransactionRequest $request)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $file = $request->file('transaction');

        if ($this->transactionManager->storeCsvTemporarily($file)) {
            $filename = str_replace(' ', '', $file->getClientOriginalName());
            $this->transactionManager->startImport($filename)
                                     ->fireCsvUploadEvent($filename, $id);

            $response = null;

            if (!$this->transactionManager->isInUTF8Encoding($filename)) {
                $response = ['type' => 'warning', 'code' => ['encoding_error', ['message' => trans('error.something_is_not_right')]]];
            }

            return redirect()->route('activity.import-transaction.status', [$id])->withResponse($response);
        }

        $response = ['type' => 'danger', 'code' => ['csv_header_mismatch', ['message' => trans('error.something_is_not_right')]]];

        return redirect()->to('Activity.transaction.uploader')->withResponse($response);
    }

    /**
     * Download Simple Transaction Template.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSimpleTransactionTemplate()
    {
        $file = $this->transactionManager->downloadSimpleTransactionTemplate(session('version'));

        return response()->download($file);
    }

    /**
     * Download Detailed Transaction Template.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadDetailedTransactionTemplate()
    {
        $file = $this->transactionManager->downloadDetailedTransactionTemplate(session('version'));

        return response()->download($file);
    }

    /**
     * Return view that displays the status of the transaction import.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function status($id)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        return view('Activity.csvImporter.transaction.status', compact('id'));
    }

    /**
     * Ajax request to return status of transaction importer.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus($id)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return response()->json(['status' => trans('error.no_correct_privilege')]);
        }

        $status = $this->transactionManager->checkStatus($this->getOrgId(), $this->getUserId(), $id);

        if (empty($status)) {
            $status = 'no_ongoing_processes';
        }

        return response()->json(['status' => $status]);
    }

    /**
     * Returns data that has been processed and stored in json file.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData($id)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        if (!($response = $this->transactionManager->getData($this->getOrgId(), $this->getUserId(), $id))) {
            $response = ['render' => sprintf('<p>%s</p>', trans('error.data_not_available'))];
        }

        return response()->json($response);
    }

    /**
     * Returns user id.
     *
     * @return mixed
     */
    protected function getUserId()
    {
        if (auth()->user()) {
            return auth()->user()->id;
        }
    }

    /**
     * Returns organisation id.
     *
     * @return mixed
     */
    protected function getOrgId()
    {
        if (session()->has('org_id')) {
            return session('org_id');
        }
    }

    /**
     * Cancel the transaction import process.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->transactionManager->cancel($this->getOrgId(), $this->getUserId(), $id);

        return redirect()->route('activity.transaction.upload-csv', [$id]);
    }

    /**
     * Store validated transactions in database.
     *
     * @param Request $request
     * @param         $id
     * @return mixed
     */
    public function validatedTransactions(Request $request, $id)
    {
        $transactions = $request->get('transactions');

        if ($transactions) {
            $this->transactionManager->storeValidatedTransactions($transactions, $this->getOrgId(), $this->getUserId(), $id);
            $this->transactionManager->cancel($this->getOrgId(), $this->getUserId(), $id);

            $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('element.transactions')]]];

            return redirect()->route('activity.transaction.index', $id)->withResponse($response);
        } else {
            return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => trans('error.select_transactions_to_be_imported')]]]);
        }
    }

    /**
     * Redirect if the uploaded csv template is mismatched or any error is present.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadRedirect($id)
    {
        $activity = $this->activityManager->getActivityData($id);
        $response = null;

        if (Gate::denies('ownership', $activity)) {
            $response = ['type' => 'warning', 'code' => ['message', ['message' => trans('error.no_correct_privilege')]]];
        }

        $form = $this->transactionUploadForm->createForm($id);

        if (!isset($response)) {
            $status = $this->transactionManager->checkStatus($this->getOrgId(), $this->getUserId(), $id);
            if (!empty($status)) {
                $response = ['type' => 'warning', 'code' => ['message', ['message' => trans(sprintf('error.%s', $status))]]];
            }
        }

        $this->transactionManager->cancel($this->getOrgId(), $this->getUserId(), $id);


        return view('Activity.transaction.uploader', compact('form', 'activity', 'id', 'response'));
    }

    /**
     * Returns valid data that has not been displayed.
     *
     * @param $id
     * @return null
     */
    public function getRemainingValidData($id)
    {
        return $this->transactionManager->getValidData($this->getOrgId(), $this->getUserId(), $id);
    }

    /**
     * Returns remaining invalid data that has not been displayed.
     *
     * @param $id
     * @return null
     */
    public function getRemainingInvalidData($id)
    {
        return $this->transactionManager->getInvalidData($this->getOrgId(), $this->getUserId(), $id);
    }
}

