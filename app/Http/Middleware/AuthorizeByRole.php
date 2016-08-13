<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Core\V201\Traits\RolePermissionTrait;
use App\Http\Middleware\Traits\ChecksPermissionForRole;

class AuthorizeByRole
{
    use RolePermissionTrait, ChecksPermissionForRole;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->hasPermissionFor($request)) {
            return $next($request);
        }

        if ($this->checkPermission($request->route())) {
            return $next($request);
        }

        $response = ['type' => 'warning', 'code' => ['message', ['message' => "You don't have correct privilege"]]];

        return redirect(config('app.admin_dashboard'))->withResponse($response);
    }
}
