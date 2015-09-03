<?php namespace App\Http\Controllers\Organization\Complete;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrgReportingOrgRequest;
use App\Services\Organization\OrganizationManager;
use App\Services\Organization\OrgReportingOrgManager;
use Session;
use URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Services\FormCreator\Organization\OrgReportingOrgForm;

class OrgReportingOrgController extends Controller
{

    protected $formBuilder;
    protected $organizationManager;
    protected $orgReportingOrgManager;
    protected $orgReportingOrgFormCreator;

    function __construct(
        OrgReportingOrgForm $orgReportingOrgFormCreator,
        OrganizationManager $organizationManager,
        OrgReportingOrgManager $orgReportingOrgManager
    ) {
        $this->middleware('auth');
        $this->orgReportingOrgFormCreator = $orgReportingOrgFormCreator;
        $this->organizationManager = $organizationManager;
        $this->orgReportingOrgManager = $orgReportingOrgManager;

    }

    /**
     * @param $organizationId
     */
    public function index($organizationId)
    {
//        dd($organizationId);

    }

    /**
     * @param $organizationId
     * @return \Illuminate\View\View
     */
    public function create($organizationId)
    {
        $organization = $this->organizationManager->getOrganization($organizationId);
        $form = $this->orgReportingOrgFormCreator->create($organizationId);
        return view('organization.reportingOrg.create', compact('form', 'narrativeForm', 'organization'));
    }
    /**
     * @param $organizationId
     * @param CreateOrgReportingOrgRequest $request
     * @return mixed
     */
    public function store($organizationId, CreateOrgReportingOrgRequest $request)
    {
        $input = Input::all();
        $organization = $this->organizationManager->getOrganization($organizationId);
        $this->orgReportingOrgManager->create($organization, $input);
        Session::flash('message', 'Reporting Organization created !');
        return Redirect::to('organization');
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $organizationId
     * @return Response
     */
    public function edit($organizationId)
    {
        $organization = $this->organizationManager->getOrganization($organizationId);
        $data['reportingOrg'] = $organization->buildOrgReportingOrg();
        $form = $this->orgReportingOrgFormCreator->editForm($data, $organization);
        return view('organization.reportingOrg.edit', compact('form', 'organization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $organizationId
     * @return Response
     */
    public function update($organizationId)
    {
        $input = Input::all();
        $organization = $this->organizationManager->getOrganization($organizationId);
        $this->orgReportingOrgManager->update($input, $organization);
        Session::flash('message', 'Reporting Organization Updated !');
        return Redirect::to('organization');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}
