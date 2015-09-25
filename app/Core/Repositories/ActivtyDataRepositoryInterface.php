<?php

namespace App\Core\Repositories;

interface ActivityDataRepositoryInterface
{
    public function createActivity(array $input);

    public function getActivities();

    public function getActivity($id);

    public function updateActivity($input, $activity);
}
