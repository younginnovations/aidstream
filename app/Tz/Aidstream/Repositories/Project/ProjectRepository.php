<?php namespace App\Tz\Aidstream\Repositories\Project;

use App\Tz\Models\Project;

/**
 * Class ProjectRepository
 * @package App\Tz\Aidstream\Repositories\Project
 */
class ProjectRepository implements ProjectRepositoryInterface
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * ProjectRepository constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {

    }
}
