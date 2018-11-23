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
    protected $allowedSubdomains = ['tz', 'np'];

    /**
     * Code to redirect to main AidStream
     */
    const MAIN_ROUTE_CODE = 801;

    /**
     * Code to redirect to Tz AidStream
     */
    const TZ_ROUTE_CODE = 802;

    /**
     * Code to redirect to Np Aidstream
     */
    const NP_ROUTE_CODE = 804;

    /**
     * Code to redirect to the same AidStream
     */
    const INTERNAL_REDIRECT_CODE = 803;

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
                } else if(isNpSubDomain()) {
                    return redirect()->route('np.home');
                }

                return redirect()->route('main.home');
            }
        }

        if ($user = $this->auth->user()) {
            if (superAdminIsLoggedIn()) {
                return $next($request);
            } else {
                $responseCode    = $this->userIsAllowed($user, $request);
                $versionMetaData = $this->parseVersion($user);

                if (self::MAIN_ROUTE_CODE === $responseCode) {
                    $this->auth->logOut();

                    return redirect()->route('main.home');
                } else if(self::NP_ROUTE_CODE === $responseCode) {

                    return $next($request);
                } elseif (self::TZ_ROUTE_CODE === $responseCode) {
                    $this->auth->logOut();

                    return redirect()->route('tz.home');
                } elseif (self::INTERNAL_REDIRECT_CODE === $responseCode) {
                    return redirect()->route($versionMetaData['route']);
                } elseif (true === $responseCode) {
                    return $next($request);
                }
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
                $segments          = $request->segments();

                if (($currentVersion == 'Tz')) {
                    if ($registeredVersion == 'Tz') {
                        return in_array('lite', $segments) ? true : 803;
                    }

                    return 801;
                } else if($currentVersion == 'Np') {
                    if($registeredVersion == 'Np') {
                        return in_array('lite', $segments) ? true : 804;
                    }

                    return 801;
                } elseif ($currentVersion == 'Main') {
                    if ($registeredVersion == 'Lite') {
                        return in_array('lite', $segments) ? true : 803;
                    } elseif ($registeredVersion == 'Core') {
                        return in_array('lite', $segments) ? 803 : true;
                    }

                    return 802;
                } else {

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
        } else if($systemVersion == 'Np'){
            if (array_intersect($this->allowedSubdomains, $pieces) && in_array('lite', $uriPieces)){
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
        if(in_array('np', $pieces)){
            return 'Np';
        }
        return in_array('tz', $pieces) ? 'Tz' : 'Main';
    }
}
