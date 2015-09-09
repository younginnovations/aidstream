<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\RequestManager\Organization\CreateOrgRecipientOrgBudgetRequestManager;
use App\Services\FormCreator\Organization\RecipientOrgBudgetForm;
use App\Services\Organization\RecipientOrgBudgetManager;
use Session;
use URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Core\Elements\BaseElement;

class RecipientOrganizationBudgetController extends Controller {

	protected $formBuilder;
	protected $recipientOrgBudgetManager;
	protected $recipientOrgBudgetFormCreator;

	public function __construct(
		RecipientOrgBudgetForm $recipientOrgBudgetFormCreator,
		RecipientOrgBudgetManager $recipientOrgBudgetManager
	)
	{
		$this->middleware('auth');
		$this->recipientOrgBudgetFormCreator = $recipientOrgBudgetFormCreator;
		$this->recipientOrgBudgetManager = $recipientOrgBudgetManager;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($organizationId)
	{
		$organizationData = $this->recipientOrgBudgetManager->getOrganizationData($organizationId);
		$data['recipientOrganizationBudget'] = $organizationData->buildRecipientOrgBudget();
		$form = $this->recipientOrgBudgetFormCreator->editForm($data, $organizationId);
		return view('Organization.recipientOrgBudget.edit', compact('form', 'organization'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $organizationId
	 * @return Response
	 */
	public function update($organizationId, CreateOrgRecipientOrgBudgetRequestManager $request)
	{
		$input = Input::all();
        $organization = $this->recipientOrgBudgetManager->getOrganizationData($organizationId);
        $this->recipientOrgBudgetManager->update($input, $organization);
        Session::flash('message', 'Recipient Organization Budget Updated !');
        return Redirect::to("organization/$organizationId");
	}

}
