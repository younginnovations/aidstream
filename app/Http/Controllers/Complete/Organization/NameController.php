<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Organization\OrganizationData;
use App\Services\Organization\OrganizationDataManager;
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
	protected $organizationDataManager;
	protected $nameManager;
	protected $nameForm;
	public function __construct(
		FormBuilder $formBuilder,
		OrganizationDataManager $organizationDataManager,
//		OrganizationManager $organizationManager,
		OrgNameManager $nameManager
	)
	{
		$this->middleware('auth');
		$this->nameForm = $formBuilder;
		$this->org_id = Session::get('org_id');
		$this->organizationDataManager = $organizationDataManager;
//		$this->organizationManager = $organizationManager;
		$this->nameManager = $nameManager;
		$this->nameData = $this->nameManager->getOrganizationNameData($this->org_id);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($orgId)
	{
		$form = $this->nameForm->editForm($this->nameData, $orgId);
		return view('Organization.name.create', compact('form'));
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
	public function store($orgId, NameRequestManager $nameRequestManager)
	{
		$input = Input::all();
		$this->nameManager->create($orgId, $input);
		Session::flash('message', 'Name created !');
		return Redirect::to('organization/');
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
