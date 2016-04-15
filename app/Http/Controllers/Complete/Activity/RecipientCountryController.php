<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\TransactionManager;
use App\Services\FormCreator\Activity\RecipientCountry as RecipientCountryForm;
use App\Services\Activity\RecipientCountryManager;
use App\Services\RequestManager\Activity\RecipientCountry as RecipientCountryRequestManager;
use Illuminate\Support\Facades\Gate;

/**
 * Class RecipientCountryController
 * @package app\Http\Controllers\Complete\Activity
 */
class RecipientCountryController extends Controller
{
    /**
     * @var RecipientCountryForm
     */
    protected $recipientCountryForm;

    /**
     * @var RecipientCountryManager
     */
    protected $recipientCountryManager;

    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * @var TransactionManager
     */
    protected $transactionManager;

    /**
     * @param RecipientCountryForm    $recipientCountryForm
     * @param RecipientCountryManager $recipientCountryManager
     * @param ActivityManager         $activityManager
     * @param TransactionManager      $transactionManager
     */
    public function __construct(
        RecipientCountryForm $recipientCountryForm,
        RecipientCountryManager $recipientCountryManager,
        ActivityManager $activityManager,
        TransactionManager $transactionManager
    ) {
        $this->middleware('auth');
        $this->recipientCountryForm    = $recipientCountryForm;
        $this->recipientCountryManager = $recipientCountryManager;
        $this->activityManager         = $activityManager;
        $this->transactionManager      = $transactionManager;
    }

    /**
     * returns recipient country edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $activityData     = $this->activityManager->getActivityData($id);
        $recipientCountry = $this->recipientCountryManager->getRecipientCountryData($id);
        $form             = $this->recipientCountryForm->editForm($recipientCountry, $id);

        return view('Activity.recipientCountry.edit', compact('form', 'id', 'activityData'));
    }

    /**
     * updates recipient country
     * @param                                $id
     * @param Request                        $request
     * @param RecipientCountryRequestManager $recipientCountryRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, RecipientCountryRequestManager $recipientCountryRequestManager)
    {
        $activityData  = $this->activityManager->getActivityData($id);

        if (Gate::denies('ownership', $activityData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorizeByRequestType($activityData, 'recipient_country');
        $activityTransactions = $this->transactionManager->getTransactions($id);
        $count                = 0;

        if ($activityTransactions) {
            foreach ($activityTransactions as $transactions) {
                $transactionDetail = $transactions->transaction;
                removeEmptyValues($transactionDetail);
                if (!Empty($transactionDetail['recipient_country']) || !Empty($transactionDetail['recipient_region'])) {
                    $count ++;
                }
            }
        }

        if ($count > 0) {
            $response = [
                'type' => 'warning',
                'code' => ['message', ['message' => 'You cannot save Recipient Country in activity level because you have already saved recipient country or region in transaction level.']]
            ];

            return redirect()->back()->withInput()->withResponse($response);
        }

        $recipientCountry = $request->all();
        if ($this->recipientCountryManager->update($recipientCountry, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Recipient Country']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Recipient Country']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
