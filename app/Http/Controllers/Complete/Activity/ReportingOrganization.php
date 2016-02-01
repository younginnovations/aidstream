<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity\Activity;
use App\Services\Organization\OrganizationManager;
use Illuminate\Support\Facades\Session;

class ReportingOrganization extends Controller
{
    /**
     * write brief description
     * @param                     $id
     * @param OrganizationManager $organizationManager
     * @return \Illuminate\View\View
     */
    public function index($id, OrganizationManager $organizationManager)
    {
        $activity = Activity::find($id);

        if ($activity) {
            $reportingOrganization = $organizationManager->getOrganization(Session::get('org_id'))->reporting_org;

            return view('Activity.ReportingOrganization.edit', compact('reportingOrganization', 'id'));
        }

        return redirect()->route('activity.index')->withResponse(['messages' => ['Activity with id' . $id . ' not found.'], 'type' => 'danger']);
    }
}
