<?php namespace App\Core\SuperAdmin;

use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;
use App\Models\OrganizationPublished;

/**
 * Class OrganizationService
 * @package App\Core\SuperAdmin
 */
class OrganizationService
{
    /**
     * @var Organization
     */
    protected $organization;
    /**
     * @var ActivityPublished
     */
    protected $activityPublished;
    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @var OrganizationPublished
     */
    protected $organizationPublished;

    /**
     * OrganizationService constructor.
     * @param Organization      $organization
     * @param Activity          $activity
     * @param ActivityPublished $activityPublished
     */
    public function __construct(Organization $organization, Activity $activity, ActivityPublished $activityPublished, OrganizationPublished $organizationPublished)
    {
        $this->organization          = $organization;
        $this->activity              = $activity;
        $this->activityPublished     = $activityPublished;
        $this->organizationPublished = $organizationPublished;
    }

    /**
     * Find an Organization with a specific organizationId.
     * @param $organizationId
     * @return mixed
     */
    public function find($organizationId)
    {
        return $this->organization->findOrFail($organizationId);
    }

    /**
     * Gets a record from ActivityPublished with the specific fileId.
     * @param $fileId
     * @return mixed
     */
    public function findPublishedFile($fileId)
    {
        return $this->activityPublished->findOrFail($fileId);
    }

    /**
     * Updates publish to register field in ActivityPublished.
     * @param ActivityPublished $file
     * @return bool|null
     */
    public function updateUnPublished(ActivityPublished $file)
    {
        $file->published_to_register = 0;

        if (!$file->save()) {
            return null;
        }

        return true;
    }

    /**
     * Gets a record from OrganizationPublished with the specific fileId.
     * @param $fileId
     * @return mixed
     */
    public function findOrganizationFile($fileId)
    {
        return $this->organizationPublished->findOrFail($fileId);
    }

    public function updateOrganizationPublished(OrganizationPublished $file)
    {
        $file->published_to_register = 0;

        if (!$file->save()) {
            return null;
        }

        return true;
    }
}
