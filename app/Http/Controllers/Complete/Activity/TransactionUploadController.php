<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\UploadTransactionManager;
use App\Services\FormCreator\Activity\UploadTransaction;
use App\Services\RequestManager\Activity\CsvImportValidator;
use App\Services\RequestManager\Activity\UploadTransaction as UploadTransactionRequest;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $form = $this->uploadTransaction->createForm($id);

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
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $activity);
        $file = $request->file('transaction');

        if ($this->uploadTransactionManager->isEmptyCsv($file)) {
            return redirect()->back()
                             ->withResponse(
                                 [
                                     'type' => 'danger',
                                     'code' => ['empty_template', ['name' => 'Transaction']]
                                 ]
                             );
        }

        $validator = $this->validatorForCurrentCsvType($file, $csvImportValidator);

        if (null === $validator) {
            return redirect()->back()->withResponse(['type' => 'danger', 'code' => ['header_mismatch', ['name' => 'Transaction']]]);
        }

        if (null !== $validator && $validator->fails()) {
            $response = ['type' => 'danger', 'messages' => $validator->errors()->all()];

            return redirect()->back()->withInput()->withResponse($response);
        }

        $this->uploadTransactionManager->save($file, $activity);
        $this->activityManager->resetActivityWorkflow($id);
        $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Transactions']]];

        return redirect()->to(sprintf('/activity/%s/transaction', $id))->withResponse($response);
    }

    /**
     * Validate csv according to the file type (detailed or simple csv)
     * @param UploadedFile       $file
     * @param CsvImportValidator $csvImportValidator
     * @return null
     */
    protected function validatorForCurrentCsvType(UploadedFile $file, CsvImportValidator $csvImportValidator)
    {
        if ($this->uploadTransactionManager->isDetailedCsv($file)) {
            return $csvImportValidator->validator->getDetailedCsvValidator($file);
        }

        if ($this->uploadTransactionManager->isSimpleCsv($file)) {
            return $csvImportValidator->validator->getSimpleCsvValidator($file);
        }

        return null;
    }
}
