<?php
namespace app\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

class ActivityRepository
{
    protected $activity;

    public function __construct(
        Activity $activity
    ) {
        $this->activity = $activity;
    }

    public function store(array $input)
    {
        unset($input['_token']);
        $this->activity->create(
            [
                'identifier'      => $input,
                'organization_id' => 1
            ]
        );
    }
}
