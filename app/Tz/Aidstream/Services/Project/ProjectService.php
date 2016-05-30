<?php namespace App\Tz\Aidstream\Services\Project;

use App\Tz\Aidstream\Models\Project;
use App\Tz\Aidstream\Repositories\Project\ProjectRepositoryInterface;
use App\User;
use Exception;
use Psr\Log\LoggerInterface;

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
     * @var User
     */
    protected $user;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * ProjectService constructor.
     * @param ProjectRepositoryInterface $project
     * @param User                       $user
     * @param LoggerInterface            $logger
     */
    public function __construct(ProjectRepositoryInterface $project, User $user, LoggerInterface $logger)
    {
        $this->project = $project;
        $this->user    = $user;
        $this->logger  = $logger;
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

    /**
     * Get all Projects for an Organization.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->project->all();
    }

    /**
     * Create a new Project.
     * @param array $projectDetails
     * @return bool|null
     */
    public function create(array $projectDetails)
    {
        try {
            $projectDetails['organization_id'] = session('org_id');
            $this->project->create($projectDetails);

            $this->logger->info(
                'Project successfully created.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Project could not created due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute(),
                    'trace'  => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }

    /**
     * Delete an existing Project.
     * @param $id
     * @return bool|null
     */
    public function delete($id)
    {
        try {
            $project        = $this->project->find($id);
            $publishedFiles = $project->organization->publishedFiles;

            $this->project->delete($id);
            $this->removePublishedFilesAssociation($id, $publishedFiles);

            $this->logger->info(
                sprintf('Project (id: %s) successfully deleted.', $id),
                [
                    'byUser'          => auth()->user()->getNameAttribute(),
                    'organization_id' => session('org_id')
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error while deleting Project (id: %s) due to %s.', $id, $exception->getMessage()),
                [
                    'byUser'          => auth()->user()->getNameAttribute(),
                    'organization_id' => session('org_id'),
                    'trace'           => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }

    /**
     * @param $id
     * @param $defaultFieldValues
     * @return bool|null
     */
    public function updateDefaults($id, $defaultFieldValues)
    {
        try {
            $project = $this->project->find($id);

            $project->update($defaultFieldValues);
            $this->resetWorkflow($project);

            $this->logger->info(
                'Successfully updated the Default Field Values.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error updating the Default Field Values due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute(),
                    'trace'  => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }

    /**
     * Update an existing Project.
     * @param       $id
     * @param array $projectDetails
     * @return bool|null
     */
    public function update($id, array $projectDetails)
    {
        try {
            $this->project->update($id, $projectDetails);
            $this->resetWorkflow($this->project->find($id));

            $this->logger->info(
                'Project successfully updated.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Project could not be updated due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttribute(),
                    'trace'  => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }

    /**
     * Get all Published Files for the current Organization.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPublishedFiles()
    {
        return $this->project->getPublishedFiles(session('org_id'));
    }

    /**
     * Reset Project Workflow on update.
     * @param Project $project
     */
    protected function resetWorkflow(Project $project)
    {
        $project->activity_workflow = 0;

        $project->save();
    }

    /**
     * List the Current Organization's Users.
     * @return array|static[]
     */
    public function getUsers()
    {
        return $this->user->getUserByOrgIdAndRoleId();
    }

    /**
     * Duplicate an existing Project.
     * @param Project $project
     * @return bool|null
     */
    public function duplicate(Project $project)
    {
        try {
            $this->project->duplicate($project);

            $this->logger->info(
                'Project successfully duplicated.',
                [
                    'byUser' => auth()->user()->getNameAttibute
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Project could not be duplicated due to %s', $exception->getMessage()),
                [
                    'byUser' => auth()->user()->getNameAttibute,
                    'trace'  => $exception->getTraceAsString()
                ]
            );

            return null;
        }
    }

    /**
     * Remove deleted Project from published_activities column of ActivityPublished table.
     * @param $projectId
     * @param $publishedFiles
     */
    protected function removePublishedFilesAssociation($projectId, $publishedFiles)
    {
        foreach ($publishedFiles as $publishedFile) {
            $containedActivities = $publishedFile->extractActivityId();

            foreach ($containedActivities as $id => $filename) {
                if ($id == $projectId) {
                    $containedActivities = array_except($containedActivities, $id);
                }

                $publishedFile->published_activities = $containedActivities;
                $publishedFile->save();
            }
        }
    }
}
