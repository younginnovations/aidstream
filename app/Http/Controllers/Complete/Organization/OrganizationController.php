<?php namespace app\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SettingsManager;
use App\Services\Organization\OrganizationManager;
use App\Services\FormCreator\Organization\OrgReportingOrgForm;
use Illuminate\Http\Request;
use App\Services\Organization\OrgNameManager;

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
     * @param SettingsManager     $settingsManager
     * @param OrganizationManager $organizationManager
     * @param OrgReportingOrgForm $orgReportingOrgFormCreator
     * @param OrgNameManager      $nameManager
     */
    public function __construct(
        SettingsManager $settingsManager,
        OrganizationManager $organizationManager,
        OrgReportingOrgForm $orgReportingOrgFormCreator,
        OrgNameManager $nameManager,
        Request $request
    ) {
        $this->settingsManager            = $settingsManager;
        $this->organizationManager        = $organizationManager;
        $this->orgReportingOrgFormCreator = $orgReportingOrgFormCreator;
        $this->nameManager                = $nameManager;
        $this->request                    = $request;
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
        $organization                  = $this->organizationManager->getOrganization($id);
        $organizationData              = $this->nameManager->getOrganizationData($id);
        $reporting_org                 = (array) $organization->reporting_org[0];
        $org_name                      = (array) $organizationData->name;
        $total_budget                  = (array) $organizationData->total_budget;
        $recipient_organization_budget = (array) $organizationData->recipient_organization_budget;
        $recipient_country_budget      = (array) $organizationData->recipient_country_budget;
        $document_link                 = (array) $organizationData->document_link;

        $status = $organizationData->status;

        return view(
            'Organization/show',
            compact(
                'organization',
                'reporting_org',
                'org_name',
                'total_budget',
                'recipient_organization_budget',
                'recipient_country_budget',
                'document_link',
                'status'
            )
        );

    }

    /**
     * @param         $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus($id, Request $request)
    {
        $input            = $request->all();
        $organization     = $this->organizationManager->getOrganization($id);
        $organizationData = $this->organizationManager->getOrganizationData($id);
        $settings         = $this->settingsManager->getSettings($id);
        $status           = $input['status'];

        if ($status == 1) {
            if (!isset($organization->reporting_org) || !isset($organizationData->name)) {
                return redirect()->back()->withMessage('Organization data is not Complete.');
            }
        } else {
            if ($status == 3) {
                $orgElem     = $this->organizationManager->getOrganizationElement();
                $generateXml = $orgElem->getGenerateXml();
                $generateXml->generate($organization, $organizationData, $settings, $orgElem);
            }
        }
        $this->organizationManager->updateStatus($input, $organizationData);

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
        $data         = $organization->reporting_org;
        $form         = $this->orgReportingOrgFormCreator->editForm($data, $organization);

        return view('Organization.identifier.edit', compact('form', 'organization'));
    }

    /**
     * @param string $action
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function listPublishedFiles($action = '', $id = '')
    {
        if ($action == 'delete') {
            $result  = $this->organizationManager->deletePublishedFile($id);
            $message = $result ? 'File deleted successfully' : 'File couldn\'t be deleted.';

            return redirect()->back()->withMessage($message);
        }
        $org_id = $this->request->session()->get('org_id');
        $list   = $this->organizationManager->getPublishedFiles($org_id);

        return view('published-files', compact('list'));
    }
}
