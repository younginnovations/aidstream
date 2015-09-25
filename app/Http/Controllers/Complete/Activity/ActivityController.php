<?php namespace app\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationManager;
use App\Services\SettingsManager;
use Illuminate\Session\SessionManager;
use App\Services\Activity\ActivityManager;
use Illuminate\Http\Request;
use App\Services\FormCreator\Activity\Identifier;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ActivityController
 * @package app\Http\Controllers\Complete\Activity
 */
class ActivityController extends Controller
{
    protected $identifierForm;
    protected $activityManager;
    /**
     * @var SettingsManager
     */
    protected $settingsManager;
    /**
     * @var SessionManager
     */
    protected $sessionManager;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;

    /**
     * @param SettingsManager     $settingsManager
     * @param SessionManager      $sessionManager
     * @param OrganizationManager $organizationManager
     */
    function __construct(
        SettingsManager $settingsManager,
        SessionManager $sessionManager,
        OrganizationManager $organizationManager,
        Identifier $identifierForm,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');
        $this->settingsManager     = $settingsManager;
        $this->sessionManager      = $sessionManager;
        $this->organizationManager = $organizationManager;
        $this->identifierForm      = $identifierForm;
        $this->activityManager     = $activityManager;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('add_activity');
        $form                  = $this->identifierForm->create();
        $orgId                 = $this->sessionManager->get('org_id');
        $settings              = $this->settingsManager->getSettings($orgId);
        $defaultFieldValues    = $settings->default_field_values;
        $organization          = $this->organizationManager->getOrganization($orgId);
        $reportingOrganization = $organization->reporting_org;

        return view('Activity.create', compact('form', 'organization', 'reportingOrganization', 'defaultFieldValues'));
    }

    public function store(Request $request)
    {
        $input   = $request->all();
        $result  = $this->activityManager->store($input);
        $message = $result ? 'Activity added successfully.' : 'Failed to add activity.';

        return redirect()->back()->withMessage($message);
    }

    /**
     * Throw an unauthorized exception based on gate results.
     *
     * @param  string $ability
     * @param  array  $arguments
     * @return \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function createGateUnauthorizedException($ability, $arguments)
    {
        return new HttpException(403, 'This action is unauthorized.');
    }
}

