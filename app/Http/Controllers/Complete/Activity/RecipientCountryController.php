<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Activity\RecipientCountry as RecipientCountryForm;
use App\Services\Activity\RecipientCountryManager;
use App\Services\RequestManager\Activity\RecipientCountry as RecipientCountryRequestManager;

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
     * @param RecipientCountryForm    $recipientCountryForm
     * @param RecipientCountryManager $recipientCountryManager
     * @param ActivityManager         $activityManager
     */
    public function __construct(
        RecipientCountryForm $recipientCountryForm,
        RecipientCountryManager $recipientCountryManager,
        ActivityManager $activityManager
    ) {
        $this->recipientCountryForm    = $recipientCountryForm;
        $this->recipientCountryManager = $recipientCountryManager;
        $this->activityManager         = $activityManager;
    }

    /**
     * returns recipient country edit form
     * @param $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
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
        $this->authorize(['edit_activity', 'add_activity']);
        $recipientCountry = $request->all();
        $activityData     = $this->activityManager->getActivityData($id);
        if ($this->recipientCountryManager->update($recipientCountry, $activityData)) {
            $this->activityManager->resetActivityWorkflow($id);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Recipient Country']]];

            return redirect()->to(sprintf('/activity/%s', $id))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Recipient Country']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
