<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity\Activity;
use App\Services\Activity\ActivityManager;
use App\Services\Activity\IatiIdentifierManager;
use App\Services\FormCreator\Activity\Identifier;
use App\Services\Organization\OrganizationManager;
use App\Services\RequestManager\Activity\IatiIdentifierRequestManager;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;

class IatiIdentifierController extends Controller
{
    /**
     * @var Activity
     */
    protected $activity;
    /**
     * @var IatiIdentifierManager
     */
    protected $iatiIdentifierManager;
    /**
     * @var Identifier
     */
    protected $identifier;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;
    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * @var ActivityManager
     */
    protected $activityManager;

    /**
     * @param Activity              $activity
     * @param IatiIdentifierManager $iatiIdentifierManager
     * @param Identifier            $identifier
     * @param OrganizationManager   $organizationManager
     * @param SessionManager        $sessionManager
     * @param ActivityManager       $activityManager
     */
    function __construct(
        Activity $activity,
        IatiIdentifierManager $iatiIdentifierManager,
        Identifier $identifier,
        OrganizationManager $organizationManager,
        SessionManager $sessionManager,
        ActivityManager $activityManager
    ) {
        $this->middleware('auth');

        $this->activity              = $activity;
        $this->iatiIdentifierManager = $iatiIdentifierManager;
        $this->identifier            = $identifier;
        $this->organizationManager   = $organizationManager;
        $this->sessionManager        = $sessionManager;
        $this->organization_id       = $this->sessionManager->get('org_id');
        $this->activityManager       = $activityManager;
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    function index($id)
    {
        $iatiIdentifier        = $this->iatiIdentifierManager->getIatiIdentifierData($id);
        $organization          = $this->organizationManager->getOrganization($this->organization_id);
        $reportingOrganization = $organization->reporting_org;
        $form                  = $this->identifier->editForm($iatiIdentifier, $id);

        return view('Activity.iatiIdentifier.iatiIdentifier', compact('form', 'reportingOrganization', 'id'));
    }

    /**
     * @param                              $activityId
     * @param IatiIdentifierRequestManager $iatiIdentifierRequestManager
     * @param Request                      $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($activityId, IatiIdentifierRequestManager $iatiIdentifierRequestManager, Request $request)
    {
        $this->authorize(['edit_activity', 'add_activity']);
        $input              = $request->all();
        $iatiIdentifierData = $this->iatiIdentifierManager->getActivityData($activityId);

        if ($this->iatiIdentifierManager->update($input, $iatiIdentifierData)) {
            $this->activityManager->resetActivityWorkflow($activityId);

            return redirect()->to(sprintf('/activity/%s', $activityId))->withMessage('Activity Iati Identifier Updated !');
        }

        return redirect()->route('activity.iati-identifier.index', $activityId);
    }
}
