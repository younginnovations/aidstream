<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Complete\Traits\InterElementValidator;
use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\TransactionManager;
use App\Services\FormCreator\Activity\Transaction;
use App\Services\RequestManager\Activity\Transaction as TransactionRequest;
use App\Http\Requests\Request;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\Facades\Gate;

/**
 * Class TransactionController
 * @package App\Http\Controllers\Complete\Activity
 */
class TransactionController extends Controller
{
    use InterElementValidator;

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

        if (Gate::denies('ownership', $activity)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        return view('Activity.transaction.list', compact('activity', 'id'));
    }

    /**
     * creates transaction form to insert new transaction
     * @param $id
     * @return \Illuminate\View\View
     */
    public function create($id)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $activity);
        $form = $this->transactionForm->createForm($id);

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
        $activityData = $this->activityManager->getActivityData($activityId);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('add_activity', $activityData);
        $data              = $request->all();
        $activityAsAnArray = $activityData->toArray();

        if ($this->recipientCountryAndRegionAreInvalid($activityAsAnArray, $data)) {
            $response = [
                'type' => 'warning',
                'code' => [
                    'message',
                    ['message' => 'You cannot save Recipient Country or Recipient Region in transaction level because you have already saved recipient country or region in activity level.']
                ]
            ];

            return redirect()->back()->withInput()->withResponse($response);
        }

        $this->filterSector($data);
        $this->transactionManager->save($data, $activityData);
        $this->activityManager->resetActivityWorkflow($activityId);
        $response = ['type' => 'success', 'code' => ['created', ['name' => 'Transaction']]];

        return redirect()->to(sprintf('/activity/%s/transaction', $activityId))->withResponse($response);
    }

    /**
     * show transaction detail
     * @param $id
     * @param $transactionId
     * @return \Illuminate\View\View
     */
    public function show($id, $transactionId)
    {
        $activity    = $this->activityManager->getActivityData($id);
        $transaction = $this->transactionManager->getTransaction($transactionId);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        if (!$this->currentUserIsAuthorizedForTransaction($transactionId)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $transactionDetail = $transaction->getTransaction();

        return view('Activity.transaction.show', compact('transactionDetail', 'activity', 'id', 'transactionId'));
    }

    /**
     * edit transaction form
     * @param $id
     * @param $transactionId
     * @return \Illuminate\View\View
     */
    public function edit($id, $transactionId)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        if (!$this->currentUserIsAuthorizedForTransaction($transactionId)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $activity);
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
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        if (!$this->currentUserIsAuthorizedForTransaction($transactionId)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $activity);
        $transactionDetails = $transactionData = $request->except(['_token', '_method']);
        removeEmptyValues($transactionData);

        $activityDetails = $activity->toArray();
        removeEmptyValues($activityDetails);

        if ($this->recipientCountryAndRegionAreInvalid($activityDetails, $transactionDetails)) {
            $response = [
                'type' => 'warning',
                'code' => [
                    'message',
                    ['message' => 'You cannot save Recipient Country or Recipient Region in transaction level because you have already saved recipient country or region in activity level.']
                ]
            ];

            return redirect()->back()->withInput()->withResponse($response);
        }

        $this->filterSector($transactionDetails);
        $this->transactionManager->save($transactionDetails, $activity, $transactionId);
        $this->activityManager->resetActivityWorkflow($id);
        $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Transactions']]];

        return redirect()->to(sprintf('/activity/%s/transaction', $id))->withResponse($response);
    }


    /**
     * filter unnecessary sector codes
     * @param $transactionDetails
     */
    protected function filterSector(&$transactionDetails)
    {
        foreach ($transactionDetails['transaction'] as &$transaction) {
            foreach ($transaction['sector'] as &$sector) {
                if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == '') {
                    $sector['sector_category_code'] = '';
                    $sector['sector_text']          = '';
                } elseif ($sector['sector_vocabulary'] == 2) {
                    $sector['sector_code'] = '';
                    $sector['sector_text'] = '';
                } else {
                    $sector['sector_code']          = '';
                    $sector['sector_category_code'] = '';
                }
            }
        }
    }

    /**
     * delete transaction
     * @param $id
     * @param $transactionId
     * @return mixed
     */
    public function destroy($id, $transactionId)
    {
        $activity = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        if (!$this->currentUserIsAuthorizedForTransaction($transactionId)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('delete_activity', $activity);
        $transaction = $this->transactionManager->getTransaction($transactionId);

        if ($this->transactionManager->deleteTransaction($transaction)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['deleted', ['name' => 'Transaction']]];
        } else {
            $response = ['type' => 'danger', 'code' => ['delete_failed', ['name' => 'transaction']]];
        }

        return redirect()->back()->withResponse($response);
    }

    /**
     * deletes data block from transaction
     * @param $id
     * @param $transactionId
     * @param $jsonPath
     */
    public function deleteBlock($id, $transactionId, $jsonPath)
    {
        $result = $this->transactionManager->deleteBlock($transactionId, $jsonPath);
        if ($result) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['transaction_block_removed', ['element' => 'activity']]];
        } else {
            $response = ['type' => 'danger', 'code' => ['transaction_block_not_removed', ['element' => 'activity']]];
        }

        return redirect()->back()->withResponse($response);
    }
}
