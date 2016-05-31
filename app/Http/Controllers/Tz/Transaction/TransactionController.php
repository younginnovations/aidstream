<?php namespace App\Http\Controllers\Tz\Transaction;

use App\Core\Form\BaseForm;
use App\Http\Controllers\Tz\TanzanianController;
use App\Http\Requests\Request;
use App\Tz\Aidstream\Requests\TransactionRequests;
use App\Tz\Aidstream\Services\Transaction\TransactionService;

/**
 * Class TransactionController
 * @package App\Http\Controllers\Tz\Transaction
 */
class TransactionController extends TanzanianController
{

    /**
     * @var TransactionService
     */
    protected $transaction;

    /**
     * TransactionController constructor.
     * @param TransactionService $transaction
     */
    public function __construct(TransactionService $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Create new transaction
     * @param $id
     * @param $transactionType
     * @return mixed
     */
    public function createTransaction($id, $transactionType)
    {
        $baseForm = new BaseForm();
        $currency = $baseForm->getCodeList('Currency', 'Activity');

        return view('tz.transaction.create', compact('currency', 'id', 'transactionType'));
    }

    /**
     * Save transaction to DB
     * @param                             $id
     * @param Request                     $request
     * @param Request|TransactionRequests $request
     * @return mixed
     */
    public function store($id, Request $request, TransactionRequests $request)
    {
        $transactionDetails               = $request->all();
        $transactionDetails['project_id'] = $id;

        if (!$this->transaction->create($transactionDetails)) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Transaction could not be saved.']]];
        } else {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Transaction successfully saved.']]];
        }

        return redirect()->route('project.index')->withResponse($response);
    }

    /**
     * @param $projectId
     * @param $transactionType
     * @internal param $id
     */
    public function editTransaction($projectId, $transactionType)
    {
        $baseForm     = new BaseForm();
        $currency     = $baseForm->getCodeList('Currency', 'Activity');
        $transactions = $this->transaction->getTransactionsData($projectId, $transactionType, true);

        return view('tz.transaction.edit', compact('currency', 'projectId', 'transactionType', 'transactions'));
    }

    /**
     * update transaction
     * @param                     $projectId
     * @param                     $transactionType
     * @param Request             $request
     * @param TransactionRequests $transactionRequests
     * @return mixed
     */
    public function update($projectId, $transactionType, Request $request, TransactionRequests $transactionRequests)
    {
        if (!$this->transaction->update($request->all())) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Transaction could not be updated.']]];
        } else {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Transaction successfully updated.']]];
        }

        return redirect()->route('project.index')->withResponse($response);
    }

    /**
     * Delete specific transaction
     * @param $projectId
     * @param $transactionId
     * @return mixed
     */
    public function destroy($projectId, $transactionId)
    {
        $transaction = $this->transaction->find($transactionId);

        if ($this->transaction->destroy($transaction)) {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Transaction successfully deleted.']]];
        } else {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Transaction could not be deleted.']]];
        }

        return redirect()->route('project.index')->withResponse($response);
    }
}
