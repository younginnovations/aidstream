<?php namespace App\Http\Controllers\Complete\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Activity\ActivityManager;
use App\Services\SettingsManager;
use App\Services\Organization\OrganizationManager;
use App\Services\FormCreator\Organization\OrgReportingOrgForm;
use App\Http\Requests\Request;
use App\Services\Organization\OrgNameManager;
use Illuminate\Support\Facades\Gate;

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
     * @param Request             $request
     * @param ActivityManager     $activityManager
     */
    public function __construct(
        SettingsManager $settingsManager,
        OrganizationManager $organizationManager,
        OrgReportingOrgForm $orgReportingOrgFormCreator,
        OrgNameManager $nameManager,
        Request $request,
        ActivityManager $activityManager
    ) {
        $this->settingsManager            = $settingsManager;
        $this->organizationManager        = $organizationManager;
        $this->orgReportingOrgFormCreator = $orgReportingOrgFormCreator;
        $this->nameManager                = $nameManager;
        $this->request                    = $request;
        $this->activityManager            = $activityManager;
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
        $organization = $this->organizationManager->getOrganization($id);

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'organization']]];

            return redirect('/settings')->withResponse($response);
        }
        $organizationData              = $this->nameManager->getOrganizationData($id);
        $reporting_org                 = (array) $organization->reporting_org[0];
        $org_name                      = (array) $organizationData->name;
        $total_budget                  = (array) $organizationData->total_budget;
        $recipient_organization_budget = (array) $organizationData->recipient_organization_budget;
        $recipient_country_budget      = (array) $organizationData->recipient_country_budget;
        $document_link                 = (array) $organizationData->document_link;
        $recipient_region_budget       = (array) $organizationData->recipient_region_budget;
        $total_expenditure             = (array) $organizationData->total_expenditure;

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
                'status',
                'recipient_region_budget',
                'total_expenditure'
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
        $organization = $this->organizationManager->getOrganization($id);

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $input  = $request->all();
        $status = $input['status'];

        if ($status == 3) {
            $this->authorize('publish_activity', $organization);
        }
        $organization     = $this->organizationManager->getOrganization($id);
        $organizationData = $this->organizationManager->getOrganizationData($id);
        $settings         = $this->settingsManager->getSettings($id);

        $orgElem    = $this->organizationManager->getOrganizationElement();
        $xmlService = $orgElem->getOrgXmlService();
        if ($status === "1") {
            $messages = $xmlService->validateOrgSchema($organization, $organizationData, $settings, $orgElem);
            if ($messages !== '') {
                $response = ['type' => 'danger', 'messages' => $messages];

                return redirect()->back()->withResponse($response);
            }
        } else {
            if ($status === "3") {
                if (empty($settings['registry_info'][0]['publisher_id']) && empty($settings['registry_info'][0]['api_id'])) {
                    $response = ['type' => 'warning', 'code' => ['settings_registry_info', ['name' => '']]];

                    return redirect()->to('/settings')->withResponse($response);
                }
                $result = $xmlService->generateOrgXml($organization, $organizationData, $settings, $orgElem);

                if (!$result) {
                    $this->organizationManager->updateStatus($input, $organizationData);
                    $response = ['type' => 'warning', 'code' => ['publish_registry', ['name' => '']]];

                    return redirect()->back()->withResponse($response);
                }
            }
        }

        $statusLabel = ['Completed', 'Verified', 'Published'];
        $response    = ($this->organizationManager->updateStatus($input, $organizationData)) ?
            ['type' => 'success', 'code' => ['org_statuses', ['name' => $statusLabel[$status - 1]]]] :
            ['type' => 'danger', 'code' => ['org_statuses_failed', ['name' => $statusLabel[$status - 1]]]];

        return redirect()->back()->withResponse($response);
    }

    /**
     * write brief description
     * @param $id
     * @return \Illuminate\View\View
     */
    public function showIdentifier($id)
    {
        if (!$this->userBelongsToOrganization($id)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $organization = $this->organizationManager->getOrganization($id);
        $data         = $organization->reporting_org;
        $form         = $this->orgReportingOrgFormCreator->editForm($data, $organization);

        return view('Organization.identifier.edit', compact('form', 'organization', 'id'));
    }

    /**
     * @param string $action
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function listPublishedFiles($action = '', $id = '')
    {
        if ($id && !$this->currentUserIsAuthorizedToDeleteOrganizationFile($id)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        if ($action == 'delete') {
            $result   = $this->organizationManager->deletePublishedFile($id);
            $message  = $result ? 'File deleted successfully' : 'File couldn\'t be deleted.';
            $type     = $result ? 'success' : 'danger';
            $response = ['type' => $type, 'code' => ['transfer_message', ['name' => $message]]];

            return redirect()->back()->withResponse($response);
        }
        $org_id        = $this->request->session()->get('org_id');
        $list          = $this->organizationManager->getPublishedFiles($org_id);
        $activity_list = $this->activityManager->getActivityPublishedFiles($org_id);

        return view('published-files', compact('list', 'activity_list'));
    }

    /**
     * @param Request $request
     */
    public function orgBulkPublishToRegistry(Request $request)
    {
        $data       = $request->get('org_files');
        $pubFiles   = [];
        $unpubFiles = [];
        $value      = [];

        if (is_null($data)) {
            $response = ['type' => 'warning', 'code' => ['message', ['message' => 'Please select organization XML files to be published.']]];

            return redirect()->back()->withResponse($response);
        }
        foreach ($data as $datum) {
            $orgId    = explode(':', $datum)[0];
            $filename = explode(':', $datum)[1];
            $this->organizationManager->saveOrganizationPublishedFiles($filename, $orgId);
            $organization = $this->organizationManager->getOrganization($orgId);
            $settings     = $this->settingsManager->getSettings($orgId);
            $result       = $this->organizationManager->publishToRegistry($organization, $settings, $filename);
            if ($result) {
                $pubFiles[] = $filename;
            } else {
                $unpubFiles[] = $filename;
            }
        }

        if ($unpubFiles) {
            $value['unpublished'] = sprintf("The files %s could not be published to registry. Please try again.", implode(',', $unpubFiles));
        } elseif ($pubFiles) {
            $value['published'] = sprintf("The files %s have been published to registry", implode(',', $pubFiles));
        }

        return redirect()->back()->withValue($value);
    }
}
