<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
use App\Services\Organization\RecipientCountryBudgetManager;
use Session;
use URL;
use App\Http\Requests\Request;
use App\Services\RequestManager\Organization\RecipientCountryBudgetRequestManager;
use App\Services\FormCreator\Organization\RecipientCountryBudgetForm as FormBuilder;

/**
 * Class OrgRecipientCountryBudgetController
 * @package App\Http\Controllers\Complete\Organization
 */
class RecipientCountryBudgetController extends Controller
{

    protected $formBuilder;
    protected $recipientCountryBudgetManager;
    protected $recipientCountryBudgetForm;
    protected $organizationManager;

    public function __construct(FormBuilder $formBuilder, RecipientCountryBudgetManager $recipientCountryBudgetManager, OrganizationManager $organizationManager)
    {
        $this->middleware('auth');
        $this->recipientCountryBudgetForm    = $formBuilder;
        $this->recipientCountryBudgetManager = $recipientCountryBudgetManager;
        $this->organizationManager           = $organizationManager;
    }

    /**
     * @param $orgId
     * @return \Illuminate\View\View
     */
    public function index($orgId)
    {
        $recipientCountryBudget = $this->recipientCountryBudgetManager->getRecipientCountryBudgetData($orgId);
        $form                   = $this->recipientCountryBudgetForm->editForm($recipientCountryBudget, $orgId);

        return view(
            'Organization.recipientCountryBudget.recipientCountryBudget',
            compact('form', 'recipientCountryBudget','orgId')
        );
    }

    /**
     * @param                                      $orgId
     * @param RecipientCountryBudgetRequestManager $recipientCountryBudgetRequestManager
     * @param Request                              $request
     * @return mixed
     */
    public function update($orgId, RecipientCountryBudgetRequestManager $recipientCountryBudgetRequestManager, Request $request)
    {
        $input            = $request->all();
        $organizationData = $this->recipientCountryBudgetManager->getOrganizationData($orgId);

        if ($this->recipientCountryBudgetManager->update($input, $organizationData)) {
            $this->organizationManager->resetStatus($orgId);
            $response = ['type' => 'success', 'code' => ['updated', ['name' => 'Organization Recipient Country Budget']]];

            return redirect()->to(sprintf('/organization/%s', $orgId))->withResponse($response);
        }
        $response = ['type' => 'danger', 'code' => ['update_failed', ['name' => 'Organization Recipient Country Budget']]];

        return redirect()->back()->withInput()->withResponse($response);
    }
}
