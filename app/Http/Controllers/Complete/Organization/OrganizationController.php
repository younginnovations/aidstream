<?php namespace App\Http\Controllers\Complete\Organization;

use App\Core\Form\BaseForm;
use App\Core\V201\Requests\Settings\OrganizationInfoRequest;
use App\Core\V201\Traits\GetCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Services\Activity\ActivityManager;
use App\Services\FormCreator\Organization\OrgReportingOrgForm;
use App\Services\Organization\OrganizationManager;
use App\Services\Organization\OrgNameManager;
use App\Services\RequestManager\OrganizationElementValidator;
use App\Services\SettingsManager;
use App\Services\UserManager;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Gate;

/**
 * Class OrganizationController
 * @package App\Http\Controllers\Complete\Organization
 */
class OrganizationController extends Controller
{
    use GetCodes;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;
    /**
     * @var SettingsManager
     */
    protected $settingsManager;

    /**
     * @var BaseForm
     */
    protected $baseForm;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var OrgNameManager
     */
    protected $nameManager;

    protected $activityManager;

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
        $this->middleware('auth');
        $this->middleware('auth.systemVersion');
        $this->settingsManager            = $settingsManager;
        $this->organizationManager        = $organizationManager;
        $this->orgReportingOrgFormCreator = $orgReportingOrgFormCreator;
        $this->nameManager                = $nameManager;
        $this->request                    = $request;
        $this->activityManager            = $activityManager;
        $this->baseForm                   = $baseForm;
        $this->userManager                = $userManager;
        $this->mailer                     = $mailer;
    }

    /**
     *
     */
    public function index()
    {
        $organizationData = $this->organizationManager->getOrganizationData(session('org_id'));
        $reportingOrg     = $organizationData->where('is_reporting_org', true)->first();
        $participatingOrg = $organizationData->where('is_reporting_org', false);

        return view('Organization/index', compact('reportingOrg', 'participatingOrg'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $organizationData = $this->organizationManager->findOrganizationData($id);
        $organization     = $this->organizationManager->getOrganization(session('org_id'));

        if (Gate::denies('belongsToOrganization', $organizationData)) {
            return redirect()->route('activity.index')->withResponse($this->getNoPrivilegesMessage());
        }

        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'elementForm.organisation']]];

            return redirect('/settings')->withResponse($response);
        }

//        $organizationData              = $this->nameManager->getOrganizationData($id);
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
                'message',
                'organizationData'
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
        $organization     = $this->organizationManager->getOrganization($id);
        $organizationData = $this->organizationManager->getOrganizationData($id);

        if (Gate::denies('belongsToOrganization', $organizationData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $input  = $request->all();
        $status = $input['status'];

//        $organization = $this->organizationManager->getOrganization($id);
        if ($status == 3) {
            $this->authorize('publish_activity', $organization);
        }
        $settings = $this->settingsManager->getSettings($id);

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
                $this->organizationManager->updateStatus($input, $organizationData);

                if (getVal($result, ['status']) === false) {
                    return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => getVal($result, ['message'])]]]);
                }

                if (getVal($result, ['linked']) === false) {
                    return redirect()->back()->withResponse(['type' => 'success', 'code' => ['organization_published_not_linked', ['name' => '']]]);
                }

                return redirect()->back()->withResponse(['type' => 'success', 'code' => ['publish_registry_organization', ['name' => '']]]);
            }
        }

        $statusLabel = ['Completed', 'Verified', 'Published'];

        $response = ($this->organizationManager->updateStatus($input, $organizationData)) ?
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

        $organization = $this->organizationManager->findOrganizationData($id);
        $data         = $organization->identifier;
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
            $message  = $result ? trans('success.file_deleted') : trans('error.file_not_deleted');
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
            $response = ['type' => 'warning', 'code' => ['message', ['message' => trans('success.select_org_xml_file')]]];

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
            $value['unpublished'] = trans('error.failed_to_publish_to_registry', ['filename' => implode(',', $unpubFiles)]);
        } elseif ($pubFiles) {
            $value['published'] = trans('success.published_to_registry', ['filename' => implode(',', $pubFiles)]);
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
        $organization = $this->organizationManager->getOrganization(session('org_id'));
        $this->authorize('settings', $organization->settings);
        $organizationInfoResponse = $this->organizationManager->saveOrganizationInformation($request->all(), $organization);

        if ($organizationInfoResponse === "Username updated") {
            return redirect()->route('settings')->with('status', 'changed');
        }
        $response = $this->getResponse($organizationInfoResponse, trans('organisation.organisation_information'));

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
                ['name' => trans('global.settings')]
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
        $organizationData = $this->organizationManager->findOrganizationData($id);

        if (Gate::denies('belongsToOrganization', $organizationData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }
        $result = $this->organizationManager->deleteElement($organizationData, $element);

        if ($result) {
            $this->organizationManager->resetOrganizationWorkflow($organizationData);
            $response = ['type' => 'success', 'code' => ['organization_element_removed', ['element' => trans('global.activity')]]];
        } else {
            $response = ['type' => 'danger', 'code' => ['organization_element_not_removed', ['element' => trans('global.activity')]]];
        }

        return redirect()->route('organization.show', $id)->withResponse($response);
    }

    /**
     * View organization xml file
     * @param      $orgId
     * @param bool $viewErrors
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewOrganizationXml($orgId, $viewErrors = false)
    {
        $orgElem      = $this->organizationManager->getOrganizationElement();
        $organization = $this->organizationManager->getOrganization(session('org_id'));
        $xmlService   = $orgElem->getOrgXmlService();
        $xml          = $xmlService->generateTemporaryOrganizationXml(
            $organization,
            $this->organizationManager->findOrganizationData($orgId),
            $organization->settings,
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
        $orgElem      = $this->organizationManager->getOrganizationElement();
        $organization = $this->organizationManager->getOrganization(session('org_id'));
        $xmlService   = $orgElem->getOrgXmlService();
        $xml          = $xmlService->generateTemporaryOrganizationXml(
            $organization,
            $this->organizationManager->findOrganizationData($orgId),
            $organization->settings,
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
     *
     * @param $organizationId
     * @return string
     */
    public function getPublishedOrganizationStatus($organizationId)
    {
        $organizationPublished = $this->organizationManager->getPublishedOrganizationData($organizationId);

        $settings              = $this->settingsManager->getSettings($organizationId);
        $autoPublishing        = getVal($settings->toArray(), ['registry_info', 0, 'publish_files'], 'no');
        $status                = 'unlinked';

        if ($organizationPublished) {
            if ($autoPublishing == "no") {
                $status = ($organizationPublished->published_to_register == 1) ? "Linked" : "Unlinked";
            } else {
                $status = ($organizationPublished->published_to_register == 1) ? "Linked" : "unlinked";
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
            $message = trans('success.organisation_data_published_to_registry') . ' ' . "<a href='/files/xml/$filename'>$filename</a>";
        } elseif ($status == "Unlinked") {
            $message = trans('error.org_data_not_published_to_registry');
        } else {
            $message = trans('error.republish_org_data');
        }

        return $message;
    }

    /**
     * Return form to add a new partner organisation.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id)
    {
        $organizationTypes = $this->getNameWithCode('Activity', 'OrganisationType');
        $countries         = $this->getNameWithCode('Organization', 'Country');
        $organizations     = null;
        $formRoute         = sprintf('/organization/%s/store', session('org_id'));


        return view('Organization.create', compact('organizationTypes', 'organizationRoles', 'organizations', 'countries', 'id', 'formRoute'));
    }

    /**
     * Store the organisations from ajax request.
     * Store in organization_data table.
     *
     * @param         $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id, Request $request)
    {
        if ($this->organizationManager->store($id, $request->all())) {
            return response()->json(true, 200);
        }

        return response()->json(false, 500);
    }

    /**
     * Display form to edit the organization data.
     *
     * @param $orgDataId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($orgDataId)
    {
        $organization      = $this->organizationManager->findOrganizationData($orgDataId)->toArray();
        $organizations[0]  = array_only($organization, ['name', 'type', 'identifier', 'is_publisher', 'country']);
        $id                = session('org_id');
        $organizationTypes = $this->getNameWithCode('Activity', 'OrganisationType');
        $countries         = $this->getNameWithCode('Organization', 'Country');
        $formRoute         = sprintf('/organization-data/%s/update', $orgDataId);

        return view('Organization.create', compact('organizationTypes', 'organizationRoles', 'organizations', 'countries', 'id', 'formRoute'));
    }

    /**
     * Update the organization data from ajax request.
     *
     * @param         $orgDataId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($orgDataId, Request $request)
    {
        if ($this->organizationManager->update($orgDataId, $request->get('organisation'))) {
            return response()->json(true, 200);
        }

        return response()->json(false, 500);
    }


    /**
     * Delete a matched organization data
     *
     * @param $id
     * @return mixed
     */
    public function deleteOrganizationData($id)
    {
        $orgData               = $this->organizationManager->findOrganizationData($id);
        $organization          = $this->organizationManager->getOrganization($orgData->organization_id);
        $organizationPublished = $organization->organizationPublished;

        if ($orgData->is_reporting_org) {
            return redirect()->route('organization.index')->withResponse(['type' => 'danger', 'code' => ['cannot_delete_reporting_org']]);
        }

        if ($organizationPublished && $organizationPublished->published_to_register && ($organizationPublished->published_org_data && in_array($id, $organizationPublished->published_org_data))) {
            return redirect()->route('organization.index')->withResponse(['type' => 'warning', 'code' => ['cannot_delete_published_org', ['name' => trans('global.organization')]]]);
        }

        $result = $this->organizationManager->delete($orgData);

        if ($result) {
            return redirect()->route('organization.index')->withResponse(['type' => 'success', 'code' => ['deleted', ['name' => trans('global.organization')]]]);
        }

        return redirect()->route('organization.index')->withResponse(['type' => 'danger', 'code' => ['delete_failed', ['name' => trans('global.organization')]]]);
    }

    /**
     * Update OrganizationData through the publishing workflow.
     *
     * @param                              $organizationDataId
     * @param Request                      $request
     * @param OrganizationElementValidator $orgElementValidator
     * @return mixed
     */
    public function updateOrganizationDataStatus($organizationDataId, Request $request, OrganizationElementValidator $orgElementValidator)
    {
        $organization     = $this->organizationManager->getOrganization(session('org_id'));
        $organizationData = $this->organizationManager->findOrganizationData($organizationDataId);

        if (Gate::denies('belongsToOrganization', $organizationData)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $input  = $request->all();
        $status = $input['status'];

        if ($status == 3) {
            $this->authorize('publish_activity', $organization);
        }
        $settings = $organization->settings;

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

                $this->organizationManager->updateStatus($input, $organizationData);

                if (getVal($result, ['status']) === false) {
                    return redirect()->back()->withResponse(['type' => 'warning', 'code' => ['message', ['message' => getVal($result, ['message'])]]]);
                }

                if (getVal($result, ['linked']) === false) {
                    return redirect()->back()->withResponse(['type' => 'success', 'code' => ['organization_published_not_linked', ['name' => '']]]);
                }

                return redirect()->back()->withResponse(['type' => 'success', 'code' => ['publish_registry_organization', ['name' => '']]]);
            }
        }

        $statusLabel = ['Completed', 'Verified', 'Published'];

        $response = ($this->organizationManager->updateStatus($input, $organizationData)) ?
            ['type' => 'success', 'code' => ['org_statuses', ['name' => $statusLabel[$status - 1]]]] :
            ['type' => 'danger', 'code' => ['org_statuses_failed', ['name' => $statusLabel[$status - 1]]]];

        return redirect()->back()->withResponse($response);
    }

    /**
     * Unpublish an OrganizationData from the Registry.
     *
     * @param         $organizationDataId
     * @param Request $request
     * @return mixed
     */
    public function unpublishOrganizationData($organizationDataId, Request $request)
    {
        $organizationData = $this->organizationManager->findOrganizationData($organizationDataId);
        $organization     = $organizationData->organization;

        if (!$this->organizationManager->unpublishOrganization($organization, $organizationDataId)) {
            $response = ['type' => 'danger', 'code' => ['message', 'message' => trans('global.unlink_org_data_unsuccessful')]];
        } else {
            $response = ['type' => 'success', 'code' => ['message', 'message' => trans('global.successfully_unlinked_org_data')]];
        }

        return redirect()->back()->withResponse($response);
    }

    /**
     * Get the titles for Activities during the OrganizationData merge process.
     *
     * @param $organizationDataId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivityTitles($organizationDataId)
    {
        $organization         = $this->organizationManager->findOrganizationData($organizationDataId);
        $usedActivities       = $organization->used_by;
        $usedActivitiesTitles = [];

        foreach ($usedActivities as $activity) {
            $usedActivitiesTitles[] = $this->activityManager->getActivityData($activity)->titleText();
        }

        return response()->json($usedActivitiesTitles);
    }

    /**
     * Merge two OrganisationData.
     *
     * @param $organizationFrom
     * @param $organizationTo
     * @return mixed
     */
    public function mergeOrganizations($organizationFrom, $organizationTo)
    {
        $from = $this->organizationManager->findOrganizationData($organizationFrom);
        $to   = $this->organizationManager->findOrganizationData($organizationTo);

        $usedActivities = $from->used_by;

        foreach ($usedActivities as $activity) {
            $activity = $this->activityManager->getActivityData($activity);
            $this->replaceParticipatingOrg($activity, $from, $to);
        }

        return redirect()->route('organization.index')->withResponse(
            [
                'type' => 'success',
                'code' => ['message', 'message' => trans('organisation-data.successfully_merged')]
            ]
        );
    }

    /**
     * Replace the Participating Organization in an Activity with the another during the merge process.
     *
     * @param $activity
     * @param $from
     * @param $to
     */
    protected function replaceParticipatingOrg(&$activity, &$from, &$to)
    {
        $participatingOrganizations = collect($activity->participating_organization);
        $orgToBeReplaced            = $participatingOrganizations->filter(
            function ($organization) use ($from) {
                return array_get($organization, 'org_data_id') == $from->id;
            }
        );
        $orgToBeReplaced            = $orgToBeReplaced->first();

        $remainingOrgs = $participatingOrganizations->filter(
            function ($organization) use ($from) {
                return $organization['org_data_id'] != $from->id;
            }
        );

        $remainingOrgs->push(
            [
                'identifier'        => $to->identifier,
                'activity_id'       => $orgToBeReplaced['activity_id'],
                'organization_role' => $orgToBeReplaced['organization_role'],
                'organization_type' => $to->type,
                'country'           => $to->country,
                'org_data_id'       => $to->id,
                'narrative'         => $to->name,
                'is_publisher'      => $to->is_publisher
            ]
        );

        $from->used_by = array_diff($from->used_by, [$activity->id]);
        $from->save();

        $oldUsedBy   = $to->used_by;
        $oldUsedBy[] = $activity->id;
        $to->update(['used_by' => $oldUsedBy]);

        $activity->participating_organization = $remainingOrgs->toArray();
        $activity->save();
    }
}
