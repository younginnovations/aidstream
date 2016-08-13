<?php

namespace App\Providers;

use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Activity permissions
     * @var array
     */
    protected $permissions = ['add_activity', 'edit_activity', 'delete_activity', 'publish_activity', 'settings'];

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
            return $this->doesUserBelongToOrganization($user, $organization);
        });

        $gate->define('belongsToOrganization', function ($user, $organization) {
            return $this->doesUserBelongToOrganization($user, $organization);
        });

        $gate->define('settings-update', function ($user) {
            if ($user->isAdmin()) {
                return true;
            }
            return false;
        });

        $gate->define('update-status', function ($user, $activity) {
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                return true;
            }

            return $this->checkUserOwnershipFor($user, $activity);
        });

        $gate->define('isValidUser', function ($user, $currentUser) {
            return ($user->id == $currentUser->id);
        });

        $gate->before(function ($user, $ability, $model) {
            if ($user->isSuperAdmin()) {
                return true;
            }

            if ($user->isAdmin()) {
                if ($model instanceof Organization) {
                    if ($this->doesUserBelongToOrganization($user, $model)) {
                        return true;
                    }
                }
            }
        });


        foreach ($this->permissions as $permission) {
            $gate->define(
                $permission,
                function ($user) use ($permission) {
                    if (($user->isSuperAdmin() || $user->isAdmin())) {
                        return true;
                    } else {
                        return $user->hasPermission($permission);
                    }
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
        if ($activity instanceof Activity) {
            return ($user->org_id == $activity->organization_id);
        }

        if ($activity instanceof ActivityPublished) {
            if (!$user->isAdmin()) {
                return ($user->org_id == $activity->organization_id);
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the current user belongs to an Organization.
     * @param $user
     * @param $organization
     * @return bool
     */
    protected function doesUserBelongToOrganization($user, $organization)
    {
        if ($organization instanceof Organization) {
            return ($user->org_id == $organization->id);
        }

        return false;
    }
}

