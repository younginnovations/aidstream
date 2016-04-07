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
    protected $policies = [];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('ownership', function ($user, $activity) {
            return $this->checkUserOwnershipFor($user, $activity);
        });

        $gate->define('create', function ($user, $organization) {
            return $user->org_id == $organization->id;
        });

        $gate->define('update-status', function ($user, $activity) {
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                return true;
            }

            return $this->checkUserOwnershipFor($user, $activity);
        });

        $gate->before(
            function ($user, $ability, $activity) {
                if ($user->isAdmin() || $user->isSuperAdmin()) {
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

    /**
     * Check the ownership of an Activity by Organization's user.
     * @param $user
     * @param $activity
     * @return bool
     */
    protected function checkUserOwnershipFor($user, $activity)
    {
        return ($user->org_id == $activity->organization_id);
    }
}

