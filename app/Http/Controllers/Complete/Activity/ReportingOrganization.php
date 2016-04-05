<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity\Activity;
use App\Services\Organization\OrganizationManager;
use Illuminate\Support\Facades\Session;

class ReportingOrganization extends Controller
{
    /**
     * ReportingOrganization constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param                     $id
     * @param OrganizationManager $organizationManager
     * @return \Illuminate\View\View
     */
    public function index($id, OrganizationManager $organizationManager)
    {
        if (!$this->currentUserIsAuthorizedForActivity($id)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        $activity = Activity::find($id);

        if ($activity) {
            $reportingOrganization = $organizationManager->getOrganization(Session::get('org_id'))->reporting_org;

            return view('Activity.ReportingOrganization.edit', compact('reportingOrganization', 'id'));
        }

        return redirect()->route('activity.index')->withResponse(['messages' => ['Activity with id' . $id . ' not found.'], 'type' => 'danger']);
    }
}
