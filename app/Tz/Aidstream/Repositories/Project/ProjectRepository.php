<?php namespace App\Tz\Aidstream\Repositories\Project;

use App\Tz\Aidstream\Models\Project;


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
        return $this->project->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->project->where('organization_id', '=', session('org_id'))->get();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $projectDetails)
    {
        $project = $this->project->newInstance($projectDetails);

        return $project->save();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $project = $this->project->findOrFail($id);

        return $project->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, $projectDetails)
    {
        $project = $this->find($id);

        return $project->update($projectDetails);
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedFiles($organizationId)
    {
        return $this->project->query()
                             ->join('activity_published', 'activity_data.organization_id', '=', 'activity_published.organization_id')
                             ->where('activity_data.organization_id', '=', $organizationId)
                             ->groupBy('activity_published.id')
                             ->get(['activity_published.*']);
    }

    /**
     * {@inheritdoc}
     */
    public function duplicate(Project $project)
    {
        $projectDetails = array_except($project->toArray(), ['id', 'created_at', 'updated_at', 'activity_workflow']);
        $project        = $this->project->newInstance($projectDetails);

        return $project->save();
    }
}
