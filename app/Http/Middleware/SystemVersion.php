<?php namespace App\Http\Middleware;

use App\User;
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
     * @var array
     */
    protected $allowedSubdomains = ['tz'];

    /**
     * Code to redirect to main AidStream
     */
    const MAIN_ROUTE_CODE = 801;

    /**
     * Code to redirect to Tz AidStream
     */
    const TZ_ROUTE_CODE = 802;

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
                if (isTzSubDomain()) {
                    return redirect()->route('tz.home');
                }

                return redirect()->route('main.home');
            }
        }

        if ($user = $this->auth->user()) {
            if (superAdminIsLoggedIn()) {
                return $next($request);
            } else {
                $permission = $this->userIsAllowed($user, $request);

                if (self::MAIN_ROUTE_CODE === $permission) {
                    $this->auth->logOut();

                    return redirect()->route('main.home');
                } elseif (self::TZ_ROUTE_CODE === $permission) {
                    $this->auth->logOut();

                    return redirect()->route('tz.home');
                } elseif (true === $permission) {
                    return $next($request);
                }
                $versionMetaData = $this->parseVersion($user);

                return redirect()->route($versionMetaData['route']);
            }
        }
    }

    /**
     * Check if the user is allowed to make the request.
     *
     * @param User $user
     * @param      $request
     * @return bool|int
     */
    protected function userIsAllowed(User $user, $request)
    {
        if ($versionMetaData = $this->parseVersion($user)) {
            if ($this->isRegisteredForRoute($request, $user)) {
                return true;
            } else {
                $currentVersion    = $this->getCurrentVersion($request);
                $registeredVersion = $user->getSystemVersion();

                if (($currentVersion == 'Tz') && $registeredVersion != 'Tz') {
                    return 801;
                } else {
                    if ($currentVersion == 'Main' && ($registeredVersion != 'Lite' || $registeredVersion != 'Core')) {
                        return 802;
                    }
                }
            }

            return false;
        }

        return false;
    }

    /**
     * @param User $user
     * @return mixed
     */
    protected function parseVersion(User $user)
    {
        return config(sprintf('system-version.%s', $user->getSystemVersion()));
    }

    /**
     * Check if the request is hosted with a Subdomain.
     *
     * @param      $request
     * @param User $user
     * @return bool
     */
    protected function isRegisteredForRoute($request, User $user)
    {
        $host          = $request->getHost();
        $uriPieces     = explode('/', $request->getPathInfo());
        $pieces        = explode('.', $host);
        $systemVersion = $user->getSystemVersion();

        if ($systemVersion == 'Tz') {
            if (array_intersect($this->allowedSubdomains, $pieces) && in_array('lite', $uriPieces)) {
                return true;
            } else {
                return false;
            }
        } elseif ($systemVersion == 'Lite') {
            if (!(array_intersect($this->allowedSubdomains, $pieces)) && in_array('lite', $uriPieces)) {
                return true;
            } else {
                return false;
            }
        } elseif ($systemVersion == 'Core') {
            if (!(array_intersect($this->allowedSubdomains, $pieces)) && !(in_array('lite', $uriPieces))) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * @param $request
     * @return string
     */
    protected function getCurrentVersion($request)
    {
        $host   = $request->getHost();
        $pieces = explode('.', $host);

        return in_array('tz', $pieces) ? 'Tz' : 'Main';
    }
}
