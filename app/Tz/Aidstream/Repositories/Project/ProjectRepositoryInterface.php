<?php namespace App\Tz\Aidstream\Repositories\Project;

use App\Tz\Models\Project;

interface ProjectRepositoryInterface
{
    /**
     * Find a Project with a specific id.
     * @param $id
     * @return Project
     */
    public function find($id);
}
