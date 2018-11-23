<?php namespace App\Http\Middleware;

use App\Http\Controllers\SuperAdmin\OrganizationController;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class AuthenticateMunicipalityAdmin
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
        } elseif (isMunicipalityAdmin()) {
            return $next($request);
        }
        $response = ['type' => 'warning', 'code' => ['message', ['message' => "You don't have correct privilege"]]];

        return redirect(config('app.admin_dashboard'))->withResponse($response);
    }

}
