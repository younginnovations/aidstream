<?php namespace App\Http\Controllers\Auth\Traits;

use App\User;

trait RedirectsUsersToCorrectVersion
{
    protected $coreVersionId = 1;
    protected $liteVersionId = 2;

    protected function redirectToLite()
    {
        return redirect()->route('lite.activity.index');
    }

    protected function userIsRegisteredForLite(User $user)
    {
        if (!$user->isSuperAdmin()) {
            return ($this->getSystemVersion($user) === $this->liteVersionId);
        }

        return false;
    }

    protected function getSystemVersion(User $user)
    {
        return $user->organization ? $user->organization->system_version_id : null;
    }
}
