<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class AuthenticateAdmin
 * @package App\Http\Middleware
 */
class AuthenticateAdmin
{
    /**
     * Administrator user role id.
     */
    const ADMINISTRATOR_ROLE_ID = 5;

    /**
     * Admin user role id.
     */
    const ADMIN_ROLE_ID = 1;

    /**
     * @var Guard
     */
    protected $auth;

    /**
     * AuthenticateAdmin constructor.
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Allow only if the current user is admin/administrator.
     *
     * @param         $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        } elseif ($this->auth->user()->role_id == self::ADMIN_ROLE_ID || $this->auth->user()->role_id == self::ADMINISTRATOR_ROLE_ID) {
            return $next($request);
        }

        $response = ['type' => 'warning', 'code' => ['message', ['message' => "You don't have correct privilege"]]];

        return redirect()->route('lite.activity.index')->withResponse($response);
    }
}
