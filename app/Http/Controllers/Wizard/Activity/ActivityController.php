<?php namespace App\Http\Controllers\Wizard\Activity;

use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
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
     * @param IatiIdentifierForm  $iatiIdentifierForm
     * @param ActivityManager     $activityManager
     * @internal param IatiIdentifier $identifierForm
     */
    function __construct(
        SessionManager $sessionManager,
        OrganizationManager $organizationManager,
        IatiIdentifierForm $iatiIdentifierForm,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->organizationManager = $organizationManager;
        $this->activityManager     = $activityManager;
        $this->organization_id     = $sessionManager->get('org_id');
        $this->iatiIdentifierForm  = $iatiIdentifierForm;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
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
        $organization                    = $this->organizationManager->getOrganization($this->organization_id);
        $reportingOrganization           = $organization->reporting_org;
        $reportingOrganizationIdentifier = $reportingOrganization[0]['reporting_organization_identifier'];
        $activityIdentifier              = $request->activity_identifier;
        $input                           = [
            'activity_identifier'  => $activityIdentifier,
            'iati_identifier_text' => sprintf("%s-%s", $reportingOrganizationIdentifier, $activityIdentifier)
        ];
        $result                          = $this->activityManager->store($input, $this->organization_id);
        if (!$result) {
            return redirect()->back();
        }

        return redirect()->route('wizard.activity.title-description.index', ['id' => $result->id]);
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
