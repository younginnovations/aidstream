<?php namespace App\Http\Controllers\Complete\Activity;

use App\Http\Controllers\Controller;
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
        $reportingOrganization = $organizationManager->getOrganization(Session::get('org_id'))->reporting_org;

        return view('Activity.ReportingOrganization.edit', compact('reportingOrganization', 'id'));
    }
}
