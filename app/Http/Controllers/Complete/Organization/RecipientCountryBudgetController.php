<?php
/**
 * Created by PhpStorm.
 * User: kriti
 * Date: 9/9/15
 * Time: 2:33 PM
 */
namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Organization\RecipientCountryBudgetManager;
use Session;
use URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Services\RequestManager\Organization\RecipientCountryBudgetRequestManager;
use App\Services\FormCreator\Organization\RecipientCountryBudgetForm as FormBuilder;

/**
 * Class OrgRecipientCountryBudgetController
 * @package App\Http\Controllers\Complete\Organization
 */
class RecipientCountryBudgetController extends Controller {

    protected $formBuilder;
    protected $recipientCountryBudgetManager;
    protected $recipientCountryBudgetForm;
    public function __construct(
        FormBuilder $formBuilder,
        RecipientCountryBudgetManager $recipientCountryBudgetManager
    )
    {
        $this->middleware('auth');
        $this->recipientCountryBudgetForm = $formBuilder;
        $this->recipientCountryBudgetManager = $recipientCountryBudgetManager;
    }

    /**
     * @param $orgId
     * @return \Illuminate\View\View
     */
    public function index($orgId)
    {
        $recipientCountryBudget = $this->recipientCountryBudgetManager->getRecipientCountryBudgetData($orgId);
        $form = $this->recipientCountryBudgetForm->editForm($recipientCountryBudget, $orgId);
        return view('Organization.recipientCountryBudget.recipientCountryBudget', compact('form', 'recipientCountryBudget'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $orgId
     * @return Response
     */
    public function update($orgId)
    {
        $input = Input::all();
        $organizationData = $this->recipientCountryBudgetManager->getOrganizationData($orgId);
        $this->recipientCountryBudgetManager->update($input, $organizationData);
        Session::flash('message', 'Organization Recipient CountryBudget Updated !');
        return Redirect::to("/organization/$orgId");
    }

}
