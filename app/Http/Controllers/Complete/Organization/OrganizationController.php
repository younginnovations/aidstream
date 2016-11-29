<?php namespace App\Http\Controllers\Complete\Organization;

use App\Core\Form\BaseForm;
use App\Core\V201\Requests\Settings\OrganizationInfoRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\Activity\ActivityManager;
use App\Services\RequestManager\OrganizationElementValidator;
use App\Services\SettingsManager;
use App\Services\Organization\OrganizationManager;
use App\Services\FormCreator\Organization\OrgReportingOrgForm;
use App\Services\Organization\OrgNameManager;
use App\Services\UserManager;
use Illuminate\Contracts\Mail\Mailer;
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

    protected $baseForm;

    protected $userManager;

    protected $mailer;

    /**
     * Create a new controller instance.
     *
     * @param SettingsManager     $settingsManager
     * @param OrganizationManager $organizationManager
     * @param OrgReportingOrgForm $orgReportingOrgFormCreator
     * @param OrgNameManager      $nameManager
     * @param Request             $request
     * @param UserManager         $userManager
     * @param ActivityManager     $activityManager
     * @param Mailer              $mailer
     * @param BaseForm            $baseForm
     */
    public function __construct(
        SettingsManager $settingsManager,
        OrganizationManager $organizationManager,
        OrgReportingOrgForm $orgReportingOrgFormCreator,
        OrgNameManager $nameManager,
        Request $request,
        UserManager $userManager,
        ActivityManager $activityManager,
        Mailer $mailer,
        BaseForm $baseForm
    ) {
        $this->settingsManager            = $settingsManager;
        $this->organizationManager        = $organizationManager;
        $this->orgReportingOrgFormCreator = $orgReportingOrgFormCreator;
        $this->nameManager                = $nameManager;
        $this->request                    = $request;
        $this->activityManager            = $activityManager;
        $this->baseForm                   = $baseForm;
        $this->userManager                = $userManager;
        $this->mailer                     = $mailer;
        $this->middleware('auth');
    }

    public function index()
    {
        //
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

        if ($status == 3) {
            $organization_id        = session('org_id');
            $filename               = $this->getPublishedOrganizationFilename($organization_id);
            $organizationDataStatus = $this->getPublishedOrganizationStatus($organization_id);
            $message                = $this->getMessageForOrganizationData($organizationDataStatus, $filename);
        }

        return view(
            'Organization/show',
            compact(
                'id',
                'organization',
                'reporting_org',
                'org_name',
                'total_budget',
                'recipient_organization_budget',
                'recipient_country_budget',
                'document_link',
                'status',
                'recipient_region_budget',
                'total_expenditure',
                'organizationDataStatus',
                'message'
            )
        );
    }

    /**
     * @param                              $id
     * @param Request                      $request
     * @param OrganizationElementValidator $orgElementValidator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus($id, Request $request, OrganizationElementValidator $orgElementValidator)
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
            $validationMessage = $orgElementValidator->validateOrganization($organizationData);

            if ($validationMessage) {
                $response = ['type' => 'warning', 'code' => ['message', ['message' => $validationMessage]]];

                return redirect()->back()->withResponse($response);
            }

            $messages = $xmlService->validateOrgSchema($organization, $organizationData, $settings, $orgElem);
            if ($messages != []) {
                $response = ['type' => 'danger', 'messages' => $messages, 'organization' => 'true'];

                return redirect()->back()->withResponse($response);
            }
        } else {
            if ($status === "3") {
                if (empty($settings['registry_info'][0]['publisher_id']) && empty($settings['registry_info'][0]['api_id'])) {
                    $response = ['type' => 'warning', 'code' => ['settings_registry_info', ['name' => '']]];

                    return redirect()->to('/publishing-settings')->withResponse($response);
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

    /**
     * Display form to view organization information
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewOrganizationInformation()
    {
        $organization       = $this->organizationManager->getOrganization(session('org_id'));
        $organizationTypes  = $this->baseForm->getCodeList('OrganizationType', 'Organization');
        $countries          = $this->baseForm->getCodeList('Country', 'Organization');
        $registrationAgency = $this->baseForm->getCodeList('OrganisationRegistrationAgency', 'Organization');
        $url                = route('organization-information.update');
        $settings           = $this->settingsManager->getSettings(session('org_id'));
        $users              = $this->userManager->getAllUsersOfOrganization();

        $formOptions = [
            'method' => 'PUT',
            'url'    => $url,
            'model'  => ['narrative' => $organization->reporting_org[0]['narrative']]
        ];
        $form        = $this->organizationManager->viewOrganizationInformation($formOptions);

        return view('settings.organizationInformation', compact('form', 'organizationTypes', 'countries', 'organization', 'registrationAgency', 'settings', 'users'));
    }

    /**
     * Save organization information
     * @param OrganizationInfoRequest $request
     * @return mixed
     */
    public function saveOrganizationInformation(OrganizationInfoRequest $request)
    {
        $organization             = $this->organizationManager->getOrganization(session('org_id'));
        $this->authorize('settings', $organization->settings);
        $organizationInfoResponse = $this->organizationManager->saveOrganizationInformation($request->all(), $organization);

        if ($organizationInfoResponse === "Username updated") {
            return redirect()->route('settings')->with('status', 'changed');
        }
        $response = $this->getResponse($organizationInfoResponse, 'Organization Information');

        return redirect()->back()->withResponse($response);

    }

    /** Returns response according to status while updating information
     * @param $method
     * @param $field
     * @return array
     */
    public function getResponse($method, $field)
    {
        $response = ($method) ? [
            'type' => 'success',
            'code' => ['updated', ['name' => $field]]
        ] : [
            'type' => 'danger',
            'code' => [
                'save_failed',
                ['name' => 'Settings']
            ]
        ];

        return $response;
    }

    /** Send email to the user notifying username changed.
     * @return mixed
     */
    public function notifyUser()
    {
        $users   = $this->userManager->getAllUsersOfOrganization();
        $orgName = auth()->user()->organization->name;
        foreach ($users as $user) {
            $view            = 'emails.usernameChanged';
            $callback        = function ($message) use ($user) {
                $message->subject('AidStream Account Username changed');
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to($user->email);
            };
            $data            = $user->toArray();
            $data['orgName'] = $orgName;
            $this->mailer->send($view, $data, $callback);
        }

        $response = ['type' => 'success', 'code' => ['sent', ['name' => 'Emails']]];

        return redirect('settings')->withResponse($response);
    }

    /**
     * deletes the element which has been clicked.
     * @param $id
     * @param $element
     * @return mixed
     */
    public function deleteElement($id, $element)
    {
        $organizationData = $this->organizationManager->getOrganizationData($id);
        $organization     = $this->organizationManager->getOrganization($id);

        if (Gate::denies('belongsToOrganization', $organization)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }
        $result = $this->organizationManager->deleteElement($organizationData, $element);

        if ($result) {
            $this->organizationManager->resetOrganizationWorkflow($organizationData);
            $response = ['type' => 'success', 'code' => ['organization_element_removed', ['element' => 'activity']]];
        } else {
            $response = ['type' => 'danger', 'code' => ['organization_element_not_removed', ['element' => 'activity']]];
        }

        return redirect()->back()->withResponse($response);
    }

    /**
     * View organization xml file
     * @param      $orgId
     * @param bool $viewErrors
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewOrganizationXml($orgId, $viewErrors = false)
    {
        $orgElem    = $this->organizationManager->getOrganizationElement();
        $xmlService = $orgElem->getOrgXmlService();
        $xml        = $xmlService->generateTemporaryOrganizationXml(
            $this->organizationManager->getOrganization($orgId),
            $this->organizationManager->getOrganizationData($orgId),
            $this->settingsManager->getSettings($orgId),
            $orgElem
        );

        $xmlLines = $xmlService->getFormattedXml($xml);
        $messages = $xmlService->getSchemaErrors($xml, session('version'));

        return view('Organization.xmlView', compact('xmlLines', 'messages', 'orgId', 'viewErrors'));
    }

    /**
     * Download organization xml
     * @param $orgId
     * @return \Illuminate\Http\Response
     */
    public function downloadOrganizationXml($orgId)
    {
        $orgElem    = $this->organizationManager->getOrganizationElement();
        $xmlService = $orgElem->getOrgXmlService();
        $xml        = $xmlService->generateTemporaryOrganizationXml(
            $this->organizationManager->getOrganization($orgId),
            $this->organizationManager->getOrganizationData($orgId),
            $this->settingsManager->getSettings($orgId),
            $orgElem
        );

        return response()->make(
            $xml,
            200,
            [
                'Content-type'        => 'text/xml',
                'Content-Disposition' => sprintf('attachment; filename=orgXmlFile.xml')
            ]
        );
    }

    /** Returns filename of the published data of organization
     * @param $organization_id
     * @return string
     */
    public function getPublishedOrganizationFilename($organization_id)
    {
        $organization_data = $this->organizationManager->getPublishedOrganizationData($organization_id);

        if ($organization_data) {
            return $organization_data->filename;
        }

        return null;
    }

    /** Returns status of the published data of organization.
     * @param $organization_id
     * @return string
     */
    public function getPublishedOrganizationStatus($organization_id)
    {
        $organization_data = $this->organizationManager->getPublishedOrganizationData($organization_id);
        $settings          = $this->settingsManager->getSettings($organization_id);
        $autoPublishing    = getVal($settings->toArray(), ['registry_info', 0, 'publish_files'], 'no');
        $status            = 'unlinked';

        if ($organization_data) {
            if ($autoPublishing == "no") {
                $status = ($organization_data->published_to_register == 1) ? "Linked" : "Unlinked";
            } else {
                $status = ($organization_data->published_to_register == 1) ? "Linked" : "unlinked";
            }
        }

        return $status;
    }

    /** Returns message according to status of the organization data.
     * @param $status
     * @param $filename
     * @return string
     */
    protected function getMessageForOrganizationData($status, $filename)
    {
        if ($status == "Linked") {
            $message = "Your organization data has been published to the IATI registry. It is included in the file
                                    <a href='/files/xml/$filename'>$filename</a>";
        } elseif ($status == "Unlinked") {
            $message = "Your organization data has not been published to the IATI registry. Please go to Published files to manually publish your file to the registry. If you need help please
                                    contact us at <a href='mailto:support@aidstream.org'>support@aidstream.org</a>.";
        } else {
            $message = "Your organization data has not been published to the IATI registry. Please re-publish this activity again. If you need help please
                                    contact us at <a href='mailto:support@aidstream.org'>support@aidstream.org</a>.";
        }

        return $message;
    }
}
