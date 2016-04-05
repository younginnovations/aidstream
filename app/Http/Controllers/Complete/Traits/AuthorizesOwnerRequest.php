<?php namespace App\Http\Controllers\Complete\Traits;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityResult;
use App\Models\Activity\Transaction;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;

trait AuthorizesOwnerRequest
{
    protected function currentUserIsAuthorizedForActivity($id)
    {
        $user       = $this->getCurrentUser();
        $activities = $this->getActivitiesId($user->organization);

        if (in_array($id, $activities)) {
            return true;
        }

        return false;
    }

    protected function currentUserIsAuthorizedToDelete($fileId)
    {
        if ($this->fileBelongsToUsersOrganization($fileId)) {
            return true;
        }

        return false;
    }

    protected function getCurrentUser()
    {
        return auth()->user();
    }

    protected function fileBelongsToUsersOrganization($fileId)
    {
        $publishedFile = app()->make(ActivityPublished::class)->find($fileId);

        return ($publishedFile->organization_id == $this->getCurrentUser()->org_id);
    }

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

    protected function getNoPrivilegesMessage()
    {
        return ['type' => 'warning', 'code' => ['message', ['message' => config('permissions.no_privileges')]]];
    }

    protected function currentUserIsAuthorizedForResult($id)
    {
        $activityId       = app()->make(ActivityResult::class)->find($id)->activity_id;
        $ownersActivities = $this->getActivitiesId($this->getCurrentUser()->organization);

        if (in_array($activityId, $ownersActivities)) {
            return true;
        }

        return false;
    }

    protected function currentUserIsAuthorizedForTransaction($id)
    {
        $activityId       = app()->make(Transaction::class)->find($id)->activity_id;
        $ownersActivities = $this->getActivitiesId($this->getCurrentUser()->organization);

        if (in_array($activityId, $ownersActivities)) {
            return true;
        }

        return false;
    }

    protected function userBelongsToOrganization($organizationId)
    {
        $user = $this->getCurrentUser();

        return ($user->org_id == $organizationId);
    }
}
