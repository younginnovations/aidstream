<?php namespace App\Http\Middleware;

use App\Http\Controllers\SuperAdmin\OrganizationController;
use App\Models\Activity\Activity;
use App\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        } elseif (session('role_id') == 3) {
            $route     = $request->route();
            $routeName = $route->getName();
            if ($routeName == 'activity.show') {
                $activityId = $route->getParameter('activity');
                $orgId      = Activity::select('organization_id')->find($activityId)->organization_id;
            } elseif ($routeName == 'organization.show') {
                $orgId = $route->getParameter('organization');
            } else {
                $orgId = session('org_id');
            }

            if ($orgId) {
                $userId = User::select('id')->where('org_id', $orgId)->where('role_id', 1)->first()->id;
                app(OrganizationController::class)->masqueradeOrganization($orgId, $userId);
            } else {
                return redirect(config('app.super_admin_dashboard'));
            }
        }

        return $next($request);
    }

}
