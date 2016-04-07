<?php namespace App\Http\Controllers\Complete\Traits;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityResult;
use App\Models\Activity\Transaction;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;
use App\Models\OrganizationPublished;

/**
 * Class AuthorizesOwnerRequest
 * @package App\Http\Controllers\Complete\Traits
 */
trait AuthorizesOwnerRequest
{
    /**
     * Check if the current user's owns the Activity.
     * @param $id
     * @return bool
     */
    protected function currentUserIsAuthorizedForActivity($id)
    {
        $user       = $this->getCurrentUser();
        $activities = $this->getActivitiesId($user->organization);

        if (in_array($id, $activities)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the file being deleted is owned by the current user's Organization.
     * @param $fileId
     * @return bool
     */
    protected function currentUserIsAuthorizedToDelete($fileId)
    {
        if ($this->fileBelongsToUsersOrganization($fileId)) {
            return true;
        }

        return false;
    }

    /**
     * Returns current user.
     * @return mixed
     */
    protected function getCurrentUser()
    {
        return auth()->user();
    }

    /**
     * Check if the file belongs to the current user's Organization.
     * @param $fileId
     * @return bool
     */
    protected function fileBelongsToUsersOrganization($fileId)
    {
        $publishedFile = app()->make(ActivityPublished::class)->find($fileId);

        return ($publishedFile->organization_id == $this->getCurrentUser()->org_id);
    }

    /**
     * Returns the Activities for an Organization.
     * @param Organization $organization
     * @return array
     */
    protected function getActivitiesId(Organization $organization)
    {
        $activities  = $organization->activities;
        $activityIds = [];

        foreach ($activities as $activity) {
            if ($activity instanceof Activity) {
                $activityIds[] = $activity->id;
            }
        }

        return $activityIds;
    }

    /**
     * Return a message for no access is granted.
     * @return array
     */
    protected function getNoPrivilegesMessage()
    {
        return ['type' => 'warning', 'code' => ['message', ['message' => config('permissions.no_privileges')]]];
    }

    /**
     * Check if a ActivityResult if owned by the current user's Organization.
     * @param $id
     * @return bool
     */
    protected function currentUserIsAuthorizedForResult($id)
    {
        $activityId       = app()->make(ActivityResult::class)->find($id)->activity_id;
        $ownersActivities = $this->getActivitiesId($this->getCurrentUser()->organization);

        if (in_array($activityId, $ownersActivities)) {
            return true;
        }

        return false;
    }

    /**
     * Check if a Transaction if owned by the current user's Organization.
     * @param $id
     * @return bool
     */
    protected function currentUserIsAuthorizedForTransaction($id)
    {
        $activityId       = app()->make(Transaction::class)->find($id)->activity_id;
        $ownersActivities = $this->getActivitiesId($this->getCurrentUser()->organization);

        if (in_array($activityId, $ownersActivities)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the user belongs to an Organization with specifc organizationId.
     * @param $organizationId
     * @return bool
     */
    protected function userBelongsToOrganization($organizationId)
    {
        $user = $this->getCurrentUser();

        return ($user->org_id == $organizationId);
    }

    /**
     * Check if the current user is authorized to delete an Organization's file.
     * @param $fileId
     * @return bool
     */
    protected function currentUserIsAuthorizedToDeleteOrganizationFile($fileId)
    {
        if ($this->organizationFileBelongsToOrganizationsUser($fileId)) {
            return true;
        }

        return false;
    }

    /**
     * Check if a file belongs to the current user's Organization.
     * @param $fileId
     * @return bool
     */
    protected function organizationFileBelongsToOrganizationsUser($fileId)
    {
        $publishedFile = app()->make(OrganizationPublished::class)->find($fileId);

        return ($publishedFile->organization_id == $this->getCurrentUser()->org_id);
    }

    /**
     * Returns the ActivityPublished file with a specific fileId.
     * @param $fileId
     * @return mixed
     */
    protected function getActivityPublishedFile($fileId)
    {
        return app()->make(ActivityPublished::class)->find($fileId);
    }
}
