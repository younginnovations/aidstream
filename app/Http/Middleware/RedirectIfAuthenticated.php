<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;

class RedirectIfAuthenticated
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $redirectPaths = [
        1 => '/activity',
        2 => '/lite/activity'
    ];

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @return void
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
        if ($this->auth->check()) {
            if (($redirectUrl = $this->redirectPaths[$this->auth->user()->organization->system_version_id]) == '/activity') {
                $redirectUrl = (session('first_login')) ? '/welcome' : $redirectUrl;
            }

            return new RedirectResponse(url($redirectUrl));
        }

        return $next($request);
    }

}
