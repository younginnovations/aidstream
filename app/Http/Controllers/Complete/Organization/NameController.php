<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
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
		OrgNameManager $nameManager,
		OrganizationManager $organizationManager
	)
	{
		$this->middleware('auth');
		$this->nameForm = $formBuilder;
		$this->nameManager = $nameManager;
		$this->organizationManager = $organizationManager;
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
	 * write brief description
	 * @param                    $orgId
	 * @param NameRequestManager $nameRequestManager
	 * @param Request            $request
	 * @return mixed
     */
	public function update($orgId, NameRequestManager $nameRequestManager, Request $request)
	{
		$input            = $request->all();
		$organizationData = $this->nameManager->getOrganizationData($orgId);

		if ($this->nameManager->update($input, $organizationData)) {

			$this->organizationManager->resetStatus($orgId);
			return redirect()->to(sprintf('/organization/%s', $orgId))->withMessage(
				'Organization Name Updated !'
			);
		}
		return redirect()->route('organization.name.index',$orgId);
	}
}
