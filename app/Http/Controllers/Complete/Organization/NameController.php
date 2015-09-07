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
	protected $organizationManager;
	protected $nameManager;
	protected $nameForm;
	public function __construct(
		FormBuilder $formBuilder,
		OrganizationManager $organizationManager,
		OrgNameManager $nameManager
	)
	{
		$this->middleware('auth');
		$this->nameForm = $formBuilder;
		$this->organizationManager = $organizationManager;
		$this->nameManager = $nameManager;
		$this->settings = $this->orManager->getSettings($this->org_id);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($orgId)
	{

		$organization = $this->organizationManager->getOrganization($orgId);
		$form = $this->nameForm->create($orgId);
		return view('Organization.name.create', compact('form', 'organization'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

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
		//
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

}
