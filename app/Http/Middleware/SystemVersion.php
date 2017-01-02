<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class SystemVersion
 * @package App\Http\Middleware
 */
class SystemVersion
{
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * 'Core' System version id.
     */
    const CORE_VERSION_ID = 1;

    /**
     * 'Lite' System version id.
     */
    const LITE_VERSION_ID = 2;

    /**
     * SystemVersion constructor.
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Allow users to access routes that are associated with the their current system version.
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
        } elseif ($user = $this->auth->user()) {
            if ($user->getSystemVersion() == $this->parseVersion($request) || superAdminIsLoggedIn()) {
                return $next($request);
            }
        }

//        $response = ['type' => 'warning', 'code' => ['message', ['message' => "You haven't registered for that version."]]];

        return redirect()->route($this->getRoute($user->getSystemVersion()));
    }

    /**
     * @param $request
     * @return int
     */
    protected function parseVersion($request)
    {
        if (array_key_exists('lite', array_flip($request->segments()))) {
            return self::LITE_VERSION_ID;
        }

        return self::CORE_VERSION_ID;
    }

    /**
     * @param $versionId
     * @return string
     */
    protected function versionName($versionId)
    {
        if ($versionId == self::LITE_VERSION_ID) {
            return 'Lite';
        }

        return 'Core';
    }

    /**
     * @param $versionId
     * @return string
     */
    protected function getRoute($versionId)
    {
        if ($versionId == self::LITE_VERSION_ID) {
            return 'lite.activity.index';
        }

        return 'activity.index';
    }
}
