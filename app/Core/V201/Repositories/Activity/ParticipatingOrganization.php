<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class ParticipatingOrganization
 * @package App\Core\V201\Repositories\Activity
 */
class ParticipatingOrganization
{
    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @param Activity $activity
     */
    function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * update participating Organization
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        $activity->participating_organization = $activityDetails['participating_organization'];

        return $activity->save();
    }

    /**
     * @param $activityId
     * @return array
     */
    public function getParticipatingOrganizationData($activityId)
    {
        return $this->activity->findOrFail($activityId)->participating_organization;
    }

}
