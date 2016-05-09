<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AuthenticateOrganizationAdmin
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
        } elseif (auth()->user()->role_id == 2) {
            $response = ['type' => 'warning', 'code' => ['message', ['message' => "You don't have correct privilege"]]];

            return redirect(config('app.admin_dashboard'))->withResponse($response);
        } elseif (!session('org_id')) {
            return redirect(config('app.super_admin_dashboard'));
        }

        return $next($request);
    }
}
