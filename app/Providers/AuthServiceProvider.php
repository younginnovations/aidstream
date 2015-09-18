<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Activity permissions
     * @var array
     */
    protected $permissions = ['add_activity', 'edit_activity', 'delete_activity', 'publish_activity'];

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        $gate->before(
            function ($user, $ability) {
                if ($user->isSuperAdmin() || $user->isAdmin()) {
                    return true;
                }
            }
        );

        foreach ($this->permissions as $permission) {
            $gate->define(
                $permission,
                function ($user) use ($permission) {
                    return $user->hasPermission($permission);
                }
            );
        }
    }
}

