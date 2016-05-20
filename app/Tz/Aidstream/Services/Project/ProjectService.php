<?php namespace App\Tz\Aidstream\Services\Project;

use App\Tz\Aidstream\Repositories\Project\ProjectRepositoryInterface;
use App\Tz\Models\Project;

/**
 * Class ProjectService
 * @package App\Tz\Aidstream\Services\Project
 */
class ProjectService
{
    /**
     * @var ProjectRepositoryInterface
     */
    protected $project;

    /**
     * ProjectService constructor.
     * @param ProjectRepositoryInterface $project
     */
    public function __construct(ProjectRepositoryInterface $project)
    {
        $this->project = $project;
    }

    /**
     * Find a Project with a specific id.
     * @param $id
     * @return Project
     */
    public function find($id)
    {
        return $this->project->find($id);
    }
}
