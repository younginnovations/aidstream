<?php namespace App\Core\V201\Wizard\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class ActivityRepository
 * @package app\Core\V201\Repositories\Activity
 */
class ActivityRepository
{
    protected $activity;

    /**
     * @param Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * saves activity identifier in database
     * @param array $input
     * @param       $organizationId
     * @return static
     */
    public function store(array $input, $organizationId)
    {
        return $this->activity->create(
            [
                'identifier'      => $input,
                'organization_id' => $organizationId
            ]
        );
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getActivityData($activityId)
    {
        return $this->activity->findOrFail($activityId);
    }
}
