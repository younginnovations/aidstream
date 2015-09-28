<?php

namespace App\Core\Repositories;

use App\Models\Activity\Activity;

interface ActivityDataRepositoryInterface
{
    public function createActivity(array $activityDetails);

    public function getActivities();

    public function getActivity($id);

    public function updateActivity(array $activityDetails, Activity $activity);
}

