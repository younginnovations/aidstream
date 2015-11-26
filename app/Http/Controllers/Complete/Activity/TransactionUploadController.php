<?php namespace App\Http\Controllers\Complete\Activity;

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
     * @param Request $request
     * @param         $id
     */
    public function store(Request $request, $id, UploadTransactionRequest $uploadTransactionRequest)
    {
        $activity = $this->activityManager->getActivityData($id);
        $name     = $request->file('transaction');
        $this->uploadTransactionManager->save($name, $activity);

        return redirect()->to(sprintf('/activity/%s/transaction', $id))->withmessage('Transactions updated!');
    }
}
