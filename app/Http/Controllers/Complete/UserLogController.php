<?php namespace App\Http\Controllers\Complete;

use App\User;
use Illuminate\Http\Request;
use App\Services\ActivityLog\ActivityManager;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


/**
 * Class UserLogController
 * @package App\Http\Controllers\Complete
 */
class UserLogController extends Controller
{

    /**
     * @var ActivityManager
     */
    protected $userLogManager;

    /**
     * UserLogController constructor.
     * @param ActivityManager $userLogManager
     */
    function __construct(ActivityManager $userLogManager)
    {
        $this->middleware('auth.organizationAdmin');
        $this->middleware('auth.systemVersion');
        $this->userLogManager = $userLogManager;
    }

    /** Filter search according to the selection of the user.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {

        isset($request['userSelection']) ? $userSelection = $request['userSelection'] : $userSelection = "all";
        isset($request['dataSelection']) ? $dataSelection = $request['dataSelection'] : $dataSelection = "all";

        $usersOfOrganization      = $this->userLogManager->getUsersOfOrganization(session('org_id'));
        $activitiesOfOrganization = $this->userLogManager->getActivitiesOfOrganization(session('org_id'));
        $userLogs                 = $this->userLogManager->getResult($userSelection, $dataSelection);

        return view('ActivityLogs.user-logs', compact('usersOfOrganization', 'activitiesOfOrganization', 'userLogs', 'userSelection', 'dataSelection'));

    }

    /** View Deleted Data of the activity
     * @param $activityId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewDeletedData($activityId)
    {
        $data = $this->userLogManager->getUserActivityData($activityId);

        return view('ActivityLogs.deletedLogData', compact('data'));
    }
}
