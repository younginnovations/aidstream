<?php
namespace app\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

class ActivityRepository
{
    protected $activity;

    /**
     * @param Activity $activity
     */
    public function __construct(
        Activity $activity
    ) {
        $this->activity = $activity;
    }

    /**
     * write brief description
     * @param array $input
     * @param       $organizationId
     * @return modal
     */
    public function store(array $input, $organizationId)
    {
        unset($input['_token']);
        return $this->activity->create(
            [
                'identifier'      => $input,
                'organization_id' => $organizationId
            ]
        );
    }

    /**
     * write brief description
     * @param $organizationId
     * @return modal
     */
    public function getActivities($organizationId) {
        return $this->activity->where('organization_id', $organizationId)->get();
    }

}
