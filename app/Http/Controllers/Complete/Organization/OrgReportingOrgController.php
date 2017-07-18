<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
use App\Services\Organization\OrgReportingOrgManager;
use App\Services\RequestManager\Organization\CreateOrgReportingOrgRequestManager;
use Illuminate\Support\Facades\Gate;
use Session;
use URL;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Services\FormCreator\Organization\OrgReportingOrgForm;
use App\Services\Organization\OrgNameManager;

class OrgReportingOrgController extends Controller
{

    protected $formBuilder;
    protected $organizationManager;
    protected $orgReportingOrgManager;
    protected $orgReportingOrgFormCreator;

    /**
     * @param OrgReportingOrgForm    $orgReportingOrgFormCreator
     * @param OrganizationManager    $organizationManager
     * @param OrgReportingOrgManager $orgReportingOrgManager
     * @param OrgNameManager         $nameManager
     */
    function __construct(
        OrgReportingOrgForm $orgReportingOrgFormCreator,
        OrganizationManager $organizationManager,
        OrgReportingOrgManager $orgReportingOrgManager,
        OrgNameManager $nameManager
    ) {
        $this->middleware('auth');
        $this->orgReportingOrgFormCreator = $orgReportingOrgFormCreator;
        $this->organizationManager        = $organizationManager;
        $this->orgReportingOrgManager     = $orgReportingOrgManager;
        $this->nameManager                = $nameManager;

    }

    /**
     * @param $organizationId
     * @return \Illuminate\View\View
     */
    public function index($organizationId)
    {
        $organization = $this->organizationManager->findOrganizationData($organizationId);

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $reportingOrganization = $this->organizationManager->getOrganization(session('org_id'))->reporting_org;
        $form                  = $this->orgReportingOrgFormCreator->editForm($reportingOrganization, $organization);

        return view('Organization.reportingOrg.edit', compact('form', 'organization', 'reportingOrganization', 'organizationId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int                                $organizationId
     * @param CreateOrgReportingOrgRequestManager $createOrgReportingOrgRequestManager
     * @param Request                             $request
     * @return Response
     */
    public function update(
        $organizationId,
        CreateOrgReportingOrgRequestManager $createOrgReportingOrgRequestManager,
        Request $request
    ) {
        $organization = $this->organizationManager->getOrganization($organizationId);

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        $this->authorize('edit_activity', $organization);
        $input = $request->all();

        $this->orgReportingOrgManager->update($input, $organization);
        $this->organizationManager->resetStatus($organizationId);

        $response = ['type' => 'success', 'code' => ['updated', ['name' => trans('title.reporting_organisation')]]];

        return redirect()->route("organization.show", $organizationId)->withResponse($response);
    }
}
