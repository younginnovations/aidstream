<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Organization\OrgNameManager;
use Session;
use URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Services\RequestManager\Organization\NameRequestManager;
use App\Services\FormCreator\Organization\NameForm as FormBuilder;

class NameController extends Controller {

	protected $formBuilder;
	protected $nameManager;
	protected $nameForm;
	public function __construct(
		FormBuilder $formBuilder,
		OrgNameManager $nameManager
	)
	{
		$this->middleware('auth');
		$this->nameForm = $formBuilder;
		$this->nameManager = $nameManager;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($orgId)
	{
		$orgName = $this->nameManager->getOrganizationNameData($orgId);
		$form = $this->nameForm->editForm($orgName, $orgId);
		return view('Organization.name.edit', compact('form', 'orgName'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($orgId, NameRequestManager $nameRequestManager)
	{
		$input = Input::all();
		$organizationData = $this->nameManager->getOrganizationData($orgId);
		$this->nameManager->update($input, $organizationData);
		return redirect()->route("organization.show", $orgId)->withMessage("Name Updated !");
	}

}
