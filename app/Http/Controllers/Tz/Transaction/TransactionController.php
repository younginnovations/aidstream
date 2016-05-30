<?php namespace App\Http\Controllers\Tz\Transaction;

use App\Core\Form\BaseForm;
use App\Http\Controllers\Tz\TanzanianController;
use App\Http\Requests\Request;
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
    public function transactionCreate($id, $transactionType)
    {
        $baseForm = new BaseForm();
        $currency = $baseForm->getCodeList('Currency', 'Activity');

        return view('tz.transaction.create', compact('currency', 'id', 'transactionType'));
    }

    /**
     * Save transaction to DB
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if (!$this->transaction->create($request->all())) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Project could not be saved.']]];
        } else {
            $response = ['type' => 'success', 'code' => ['message', ['message' => 'Project successfully saved.']]];
        }

        return redirect()->route('project.index')->withResponse($response);
    }

    /**
     * @param $id
     * @param $projectId
     * @param $transactionType
     */
    public function editTransaction($id, $projectId, $transactionType)
    {
        $baseForm    = new BaseForm();
        $currency    = $baseForm->getCodeList('Currency', 'Activity');
        $transaction = $this->transaction->getTransactions($id, $transactionType);
    }
}
