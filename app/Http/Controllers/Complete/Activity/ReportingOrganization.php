<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity\Activity;
use App\Services\Organization\OrganizationManager;
use Illuminate\Support\Facades\Gate;
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
        $activity = Activity::find($id);

        if (Gate::denies('ownership', $activity)) {
            return redirect()->back()->withResponse($this->getNoPrivilegesMessage());
        }

        if ($activity) {
            $reportingOrganization = $organizationManager->getOrganization(Session::get('org_id'))->reporting_org;

            return view('Activity.ReportingOrganization.edit', compact('reportingOrganization', 'id'));
        }

        return redirect()->route('activity.index')->withResponse(['messages' => [trans('error.activity_not_found'), ['id' => $id]], 'type' => 'danger']);
    }
}
