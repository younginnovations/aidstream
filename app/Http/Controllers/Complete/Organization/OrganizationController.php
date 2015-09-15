<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SettingsManager;
use App\Services\Organization\OrganizationManager;
use App\Services\FormCreator\Organization\OrgReportingOrgForm;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class OrganizationController extends Controller {

	protected $organizationManager;
	protected $settingsManager;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(
		SettingsManager $settingsManager,
		OrganizationManager $organizationManager,
		OrgReportingOrgForm $orgReportingOrgFormCreator
	)
	{
		$this->settingsManager = $settingsManager;
		$this->organizationManager = $organizationManager;
		$this->orgReportingOrgFormCreator = $orgReportingOrgFormCreator;
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$settings = $this->settingsManager->getSettings($id);
		if(!isset($settings)) return redirect('/settings');
		$organization = $this->organizationManager->getOrganization($id);
		$reporting_org = $organization->buildOrgReportingOrg()[0];
		return view('Organization/show', compact('organization', 'reporting_org'));
	}

	/**
	 * @param $id
     */
	public function update($id)
	{
		$input = Input::all();
		if(isset($input['status'])) {
			$organization = $this->organizationManager->getOrganization($id);
			$this->organizationManager->updateStatus($input, $organization);
		}
		return Redirect::to("/organization/$id");
	}

	public function showIdentifier($id)
	{
		$organization = $this->organizationManager->getOrganization($id);
		$data = $organization->buildOrgReportingOrg()[0];
		$form = $this->orgReportingOrgFormCreator->editForm($data, $organization);
		return view('Organization.identifier.edit', compact('form', 'organization'));
	}

}