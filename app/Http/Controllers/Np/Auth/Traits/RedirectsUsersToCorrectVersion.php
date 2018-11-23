<?php namespace App\Http\Controllers\Auth\Traits;

use App\Http\Requests\Request;
use App\Models\SystemVersion;
use App\User;

/**
 * Class RedirectsUsersToCorrectVersion
 * @package App\Http\Controllers\Auth\Traits
 */
trait RedirectsUsersToCorrectVersion
{
    /**
     * Redirect Users to Lite Version Dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToLite()
    {
        return redirect()->route('lite.activity.index');
    }

    /**
     * Check if the User is registered for other versions, except Core.
     *
     * @param User $user
     * @return bool
     */
    protected function userIsRegisteredForSpecificVersion(User $user)
    {
        if (!$user->isSuperAdmin()) {
            return ($user->getSystemVersion() !== 'Core');
        }

        return false;
    }

    /**
     * Get the System Version for an Organization.
     *
     * @param User $user
     * @return SystemVersion
     */
    protected function getSystemVersion(User $user)
    {
        return $user->organization->systemVersion;
    }

    /**
     * Redirect the User of an Organization to the correct version Dashboard.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    protected function redirectToCorrectVersion(User $user)
    {
        return redirect()->route(config(sprintf('system-version.%s', $user->getSystemVersion()))['route']);
    }
}
