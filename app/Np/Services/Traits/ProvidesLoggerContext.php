<?php namespace App\Np\Services\Traits;

use App\User;
use Exception;

/**
 * Class ProvidesLoggerContext
 * @package App\Np\Services\Traits
 */
trait ProvidesLoggerContext
{
    /**
     * Get the context array for logs.
     *
     * @param Exception|null $exception
     * @return array
     */
    protected function getContext(Exception $exception = null)
    {
        //  TODO:Add org name.
        $user = auth()->user();

        $baseContext = [
            'user'            => $user->id,
            'userName'        => $user->getNameAttribute(),
            'organization_id' => $this->getOrganizationId($user),
            'organization'    => $this->getOrganizationName($user)
        ];

        if (!$exception) {
            return $baseContext;
        }

        return array_merge($baseContext, ['trace' => $exception->getTraceAsString()]);
    }

    /**
     * Get the Organization name of the current User's Organization.
     *
     * @param User $user
     * @return string
     */
    protected function getOrganizationName(User $user)
    {
        return $user->organization ? $user->organization->name : '';
    }

    /**
     * Get the Organization id of the current User's Organization.
     *
     * @param User $user
     * @return string
     */
    protected function getOrganizationId(User $user)
    {
        return $user->organization ? $user->organization->id : '';
    }
}
