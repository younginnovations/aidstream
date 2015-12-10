<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\TransactionManager;
use App\Services\FormCreator\Activity\Transaction;
use App\Services\RequestManager\Activity\Transaction as TransactionRequest;
use Illuminate\Http\Request;

/**
 * Class TransactionController
 * @package App\Http\Controllers\Complete\Activity
 */
class TransactionController extends Controller
{
    /**
     * @var ActivityManager
     */
    protected $activityManager;
    /**
     * @var Transaction
     */
    protected $transactionForm;
    /**
     * @var TransactionManager
     */
    protected $transactionManager;

    /**
     * @param ActivityManager    $activityManager
     * @param Transaction        $transactionForm
     * @param TransactionManager $transactionManager
     */
    function __construct(ActivityManager $activityManager, Transaction $transactionForm, TransactionManager $transactionManager)
    {
        $this->middleware('auth');
        $this->activityManager    = $activityManager;
        $this->transactionForm    = $transactionForm;
        $this->transactionManager = $transactionManager;
    }

    /**
     * show transaction list
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activity = $this->activityManager->getActivityData($id);

        return view('Activity.transaction.list', compact('activity', 'id'));
    }

    /**
     * creates transaction form to insert new transaction
     * @param $id
     * @return \Illuminate\View\View
     */
    public function create($id)
    {
        $this->authorize('add_activity');
        $activity = $this->activityManager->getActivityData($id);
        $form     = $this->transactionForm->createForm($id);

        return view('Activity.transaction.create', compact('form', 'activity', 'id'));
    }

    /**
     * stores transaction in database
     * @param Request            $request
     * @param                    $activityId
     * @param TransactionRequest $transactionRequest
     * @return mixed
     */
    public function store(Request $request, $activityId, TransactionRequest $transactionRequest)
    {
        $this->authorize('add_activity');
        $activity = $this->activityManager->getActivityData($activityId);
        $data     = $request->all();
        $this->transactionManager->save($data, $activity);
        $response = ['type' => 'success', 'code' => ['created', ['name' => 'Transaction']]];

        return redirect()->to(sprintf('/activity/%s/transaction', $activityId))->withResponse($response);
    }

    /**
     * show transaction detail
     * @param $activityId
     * @param $transactionId
     * @return \Illuminate\View\View
     */
    public function show($activityId, $transactionId)
    {
        $activity          = $this->activityManager->getActivityData($activityId);
        $transaction       = $this->transactionManager->getTransaction($transactionId);
        $transactionDetail = $transaction->getTransaction();

        return view('Activity.transaction.show', compact('transactionDetail', 'activity'));
    }

    /**
     * edit transaction form
     * @param $id
     * @param $transactionId
     * @return \Illuminate\View\View
     */
    public function edit($id, $transactionId)
    {
        $this->authorize('edit_activity');
        $activity    = $this->activityManager->getActivityData($id);
        $transaction = $this->transactionManager->getTransaction($transactionId);
        $form        = $this->transactionForm->editForm($activity, $transactionId, $transaction->getTransaction());

        return view('Activity.transaction.edit', compact('form', 'activity', 'id'));
    }

    /**
     * updates transaction
     * @param Request            $request
     * @param                    $id
     * @param                    $transactionId
     * @param TransactionRequest $transactionRequest
     * @return mixed
     */
    public function update(Request $request, $id, $transactionId, TransactionRequest $transactionRequest)
    {
        $this->authorize('edit_activity');
        $activity           = $this->activityManager->getActivityData($id);
        $transactionDetails = $request->except(['_token', '_method']);
        $this->transactionManager->save($transactionDetails, $activity, $transactionId);
        $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Transactions']]];

        return redirect()->to(sprintf('/activity/%s/transaction', $id))->withResponse($response);
    }

    /**
     * delete transaction
     * @param $id
     * @param $transactionId
     * @return mixed
     */
    public function destroy($id, $transactionId)
    {
        $this->authorize('delete_activity');
        $transaction = $this->transactionManager->getTransaction($transactionId);
        $response    = ($transaction->delete($transaction)) ? ['type' => 'success', 'code' => ['deleted', ['name' => 'Transaction']]] : [
            'type' => 'danger',
            'code' => ['delete_failed', ['name' => 'transaction']]
        ];

        return redirect()->back()->withResponse($response);
    }
}
