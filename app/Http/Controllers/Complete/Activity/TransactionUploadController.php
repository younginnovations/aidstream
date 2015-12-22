<?php namespace App\Http\Controllers\Complete\Activity;

use App\Core\V201\CsvImportValidator;
use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\UploadTransactionManager;
use App\Services\FormCreator\Activity\UploadTransaction;
use App\Services\RequestManager\Activity\UploadTransaction as UploadTransactionRequest;
use Illuminate\Http\Request;

class TransactionUploadController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var UploadTransaction
     */
    protected $uploadTransaction;
    /**
     * @var UploadTransactionManager
     */
    protected $uploadTransactionManager;


    /**
     * @param ActivityManager          $activityManager
     * @param UploadTransaction        $uploadTransaction
     * @param UploadTransactionManager $uploadTransactionManager
     */
    function __construct(ActivityManager $activityManager, UploadTransaction $uploadTransaction, UploadTransactionManager $uploadTransactionManager)
    {
        $this->middleware('auth');
        $this->activityManager          = $activityManager;
        $this->uploadTransaction        = $uploadTransaction;
        $this->uploadTransactionManager = $uploadTransactionManager;
    }

    /**
     * show the upload form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activity = $this->activityManager->getActivityData($id);
        $form     = $this->uploadTransaction->createForm($id);

        return view('Activity.transaction.upload', compact('form', 'activity', 'id'));
    }

    /**
     * store the uploaded transaction
     * @param Request                  $request
     * @param                          $id
     * @param UploadTransactionRequest $uploadTransactionRequest
     * @param CsvImportValidator       $csvImportValidator
     * @return $this
     */
    public function store(Request $request, $id, UploadTransactionRequest $uploadTransactionRequest, CsvImportValidator $csvImportValidator)
    {
        $this->authorize('add_activity');
        $activity  = $this->activityManager->getActivityData($id);
        $name      = $request->file('transaction');
        $validator = $csvImportValidator->isValidCsv($name);
        if ($validator->fails()) {
            $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Transactions']]];

            return redirect()->back()->withInput()->withErrors($validator)->withResponse($response);
        }
        $this->uploadTransactionManager->save($name, $activity);
        $this->activityManager->resetActivityWorkflow($id);
        $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Transactions']]];

        return redirect()->to(sprintf('/activity/%s/transaction', $id))->withResponse($response);
    }
}
