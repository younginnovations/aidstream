<?php namespace app\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SettingsManager;
use App\Services\Organization\OrganizationManager;
use App\Services\FormCreator\Organization\OrgReportingOrgForm;
use Illuminate\Http\Request;
use App\Services\Organization\OrgNameManager;
use App\Core\V201\Element\Organization\GenerateXml;

/**
 * Class OrganizationController
 * @package App\Http\Controllers\Complete\Organization
 */
class OrganizationController extends Controller
{
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;
    /**
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * Create a new controller instance.
     *
     * @param SettingsManager $settingsManager
     * @param OrganizationManager $organizationManager
     * @param OrgReportingOrgForm $orgReportingOrgFormCreator
     * @param OrgNameManager $nameManager
     */
    public function __construct(
        SettingsManager $settingsManager,
        OrganizationManager $organizationManager,
        OrgReportingOrgForm $orgReportingOrgFormCreator,
        OrgNameManager $nameManager
    ) {
        $this->settingsManager            = $settingsManager;
        $this->organizationManager        = $organizationManager;
        $this->orgReportingOrgFormCreator = $orgReportingOrgFormCreator;
        $this->nameManager                = $nameManager;
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $settings = $this->settingsManager->getSettings($id);
        if (!isset($settings)) {
            return redirect('/settings');
        }
        $organization  = $this->organizationManager->getOrganization($id);
        $organizationData = $this->nameManager->getOrganizationData($id);

        $reporting_org = $organization->buildOrgReportingOrg()[0];
        $org_name = $organizationData->name;
        $total_budget = $organizationData->total_budget;
        $recipient_organization_budget = $organizationData->recipient_organization_budget;
        $recipient_country_budget = $organizationData->recipient_country_budget;
        $document_link = $organizationData->document_link;
        if(!isset($reporting_org)) $reporting_org
            = [];
        if(!isset($org_name)) $org_name = [];
        if(!isset($total_budget)) $total_budget = [];
        if(!isset($recipient_organization_budget)) $recipient_organization_budget = [];
        if(!isset($recipient_country_budget)) $recipient_country_budget = [];
        if(!isset($document_link)) $document_link = [];

        $status = $organizationData->status;

        return view('Organization/show',
            compact('organization',
                'reporting_org',
                'org_name',
                'total_budget',
                'recipient_organization_budget',
                'recipient_country_budget',
                'document_link',
                'status'
            ));

    }

    /**
     * @param $id
     */
    public function update($id, Request $request)
    {
/*        $input = $request->all();
        $status = $input['status'];
        if (isset($status)) {
            $status = $input['status'];
            if($status == 1) {
                $organization = $this->organizationManager->getOrganization($id);
                $organizationData = $this->nameManager->getOrganizationData($id);
                if(!isset($organization->reporting_org) || !isset($organizationData->name))
                    return redirect()->back()->withMessage('Organization data is not Complete.');
            } else if($status == 3) {
                $this->generateXml($id);
            }
            $organizationData = $this->nameManager->getOrganizationData($id);
            $this->nameManager->updateStatus($input, $organizationData);
        }
        return redirect()->back();*/
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus($id, Request $request, GenerateXml $generateXml)
    {
        $input = $request->all();
        $this->organizationManager->updateStatus($input, $id, $generateXml);
        return redirect()->back();
    }

    /**
     * write brief description
     * @param $id
     * @return \Illuminate\View\View
     */
    public function showIdentifier($id)
    {
        $organization = $this->organizationManager->getOrganization($id);
        $data         = $organization->buildOrgReportingOrg()[0];
        $form         = $this->orgReportingOrgFormCreator->editForm($data, $organization);
        return view('Organization.identifier.edit', compact('form', 'organization'));
    }

}
