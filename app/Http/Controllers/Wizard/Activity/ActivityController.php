<?php namespace App\Http\Controllers\Wizard\Activity;

use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
use App\Services\SettingsManager;
use App\Services\Wizard\Activity\ActivityManager;
use App\Services\Wizard\FormCreator\Activity\IatiIdentifier as IatiIdentifierForm;
use App\Services\Wizard\RequestManager\Activity\IatiIdentifier as IatiIdentifierRequestManager;
use App\Http\Requests\Request;
use Illuminate\Session\SessionManager;

/**
 * Class ActivityController
 * @package app\Http\Controllers\Wizard\Activity
 */
class ActivityController extends Controller
{
    protected $settingsManager;
    protected $sessionManager;
    protected $organizationManager;
    protected $organization_id;
    protected $activityManager;
    /**
     * @var IatiIdentifier
     */
    protected $iatiIdentifierForm;

    /**
     * @param SessionManager      $sessionManager
     * @param OrganizationManager $organizationManager
     * @param SettingsManager     $settingsManager
     * @param IatiIdentifierForm  $iatiIdentifierForm
     * @param ActivityManager     $activityManager
     * @internal param IatiIdentifier $identifierForm
     */
    function __construct(
        SessionManager $sessionManager,
        OrganizationManager $organizationManager,
        SettingsManager $settingsManager,
        IatiIdentifierForm $iatiIdentifierForm,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->organizationManager = $organizationManager;
        $this->activityManager     = $activityManager;
        $this->organization_id     = session('org_id');
        $this->iatiIdentifierForm  = $iatiIdentifierForm;
        $this->settingsManager     = $settingsManager;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $organization = $this->organizationManager->getOrganization($this->organization_id);
        if (!isset($organization->reporting_org[0])) {
            $response = ['type' => 'warning', 'code' => ['settings', ['name' => 'activity']]];

            return redirect('/settings')->withResponse($response);
        }

        $this->authorize('add_activity');
        $form = $this->iatiIdentifierForm->create();

        return view(
            'wizard.activity.iatiIdentifier.create',
            compact('form')
        );
    }

    /**
     * save the iati identifier in database
     * @param Request                      $request
     * @param IatiIdentifierRequestManager $iatiIdentifierRequestManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, IatiIdentifierRequestManager $iatiIdentifierRequestManager)
    {
        $settings                        = $this->settingsManager->getSettings($this->organization_id);
        $defaultFieldValues              = $settings->default_field_values;
        $organization                    = $this->organizationManager->getOrganization($this->organization_id);
        $reportingOrganization           = $organization->reporting_org;
        $reportingOrganizationIdentifier = $reportingOrganization[0]['reporting_organization_identifier'];
        $activityIdentifier              = $request->activity_identifier;
        $identifier                      = [
            'activity_identifier'  => $activityIdentifier,
            'iati_identifier_text' => sprintf("%s-%s", $reportingOrganizationIdentifier, $activityIdentifier)
        ];
        $result                          = $this->activityManager->store($identifier, $defaultFieldValues);
        if (!$result) {
            $response = ['type' => 'danger', 'code' => ['message', ['message' => 'Failed to save.']]];

            return redirect()->back()->withResponse($response);
        }
        $response = ['type' => 'success', 'code' => ['message', ['message' => 'Step One Completed!']]];

        return redirect()->route('wizard.activity.title-description.index', ['id' => $result->id])->withResponse($response);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return view('activity.show', compact('id'));
    }
}
