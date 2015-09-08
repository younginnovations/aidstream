<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SettingsManager;
use App\Services\Organization\OrganizationManager;
use App\Services\FormCreator\Organization\OrgReportingOrgForm;

use Illuminate\Http\Request;

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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
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
		if(isset($settings))
			return view('Organization/show');
		else
			return redirect('/settings');

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function showIdentifier($id)
	{
		$organization = $this->organizationManager->getOrganization($id);
		$data = $organization->buildOrgReportingOrg()[0];
		$form = $this->orgReportingOrgFormCreator->editForm($data, $organization);
		return view('Organization.identifier.edit', compact('form', 'organization'));
	}

}
