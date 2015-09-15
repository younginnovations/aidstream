<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrgReportingOrgRequestManager;
use App\Services\Organization\OrganizationManager;
use App\Services\Organization\OrgReportingOrgManager;
use Session;
use URL;
use Illuminate\Http\Request;
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
        $this->nameManager     = $nameManager;

    }

    /**
     * @param $organizationId
     */
    public function index($organizationId)
    {
        $organization = $this->organizationManager->getOrganization($organizationId);
        $data         = $organization->buildOrgReportingOrg()[0];
        $form         = $this->orgReportingOrgFormCreator->editForm($data, $organization);

        return view('Organization.reportingOrg.edit', compact('form', 'organization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $organizationId
     * @return Response
     */
    public function update($organizationId)
    {
        $input['reportingOrg'][0] = Input::all();
        $organization = $this->organizationManager->getOrganization($organizationId);
        $this->orgReportingOrgManager->update($input, $organization);
        $this->nameManager->resetStatus($organizationId);
        return redirect()->route("organization.show", $organizationId)->withMessage('Reporting Organization Updated !');
    }

}
