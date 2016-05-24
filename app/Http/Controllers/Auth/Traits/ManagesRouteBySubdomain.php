<?php namespace App\Http\Controllers\Auth\Traits;

use App\User;

/**
 * Class ManagesRouteBySubdomain
 * @package App\Http\Controllers\Auth\Traits
 */
trait ManagesRouteBySubdomain
{
    /**
     * Manage route according to the subdomain used.
     * @param User $user
     * @return mixed|string
     */
    public function routeByHostFor(User $user)
    {
        $routePieces = $this->getRoutePieces();

        if ($this->hasSubdomain($routePieces)) {
            return ($user->role_id == 1 || $user->role_id == 2)
                ? route('project.index')
                : route('admin.list-organization');
        }

        return ($user->role_id == 1 || $user->role_id == 2) ? config('app.admin_dashboard') : route('admin.list-organization');
    }

    /**
     * Get the current route in pieces.
     * @return array
     */
    protected function getRoutePieces()
    {
        $host        = request()->getHost();
        $routePieces = explode('.', $host);

        return $routePieces;
    }

    /**
     * Check if the routePieces contain a subdomain.
     * @param array $routePieces
     * @return bool
     */
    public function hasSubdomain(array $routePieces)
    {
        return (count($routePieces) > 1);
    }
}
