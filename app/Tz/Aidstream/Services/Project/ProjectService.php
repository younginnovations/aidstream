<?php namespace App\Tz\Aidstream\Services\Project;

use App\Tz\Aidstream\Models\Project;
use App\Tz\Aidstream\Repositories\DocumentLink\DocumentLinkRepositoryInterface;
use App\Tz\Aidstream\Repositories\Project\ProjectRepositoryInterface;
use App\Tz\Aidstream\Traits\DocumentLinkTrait;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Psr\Log\LoggerInterface;

/**
 * Class ProjectService
 * @package App\Tz\Aidstream\Services\Project
 */
class ProjectService
{
    use DocumentLinkTrait;
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
    protected $documentLink;

    /**
     * ProjectService constructor.
     * @param ProjectRepositoryInterface      $project
     * @param User                            $user
     * @param LoggerInterface                 $logger
     * @param DocumentLinkRepositoryInterface $documentLink
     */
    public function __construct(ProjectRepositoryInterface $project, User $user, LoggerInterface $logger, DocumentLinkRepositoryInterface $documentLink)
    {
        $this->project      = $project;
        $this->user         = $user;
        $this->logger       = $logger;
        $this->documentLink = $documentLink;
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
    public function create(array $projectDetails, $request)
    {
        try {
            $projectDetails['organization_id'] = session('org_id');
            $projectId                         = $this->project->create($projectDetails);
            $documentLink                      = $this->documentLinkJsonFormat($request['document_link'], $projectId);
            $this->documentLink->create($documentLink);

            $this->logger->info(
                'Project successfully created.',
                [
                    'byUser' => auth()->user()->getNameAttribute()
                ]
            );

            return $projectId;
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
    public function update($id, array $projectDetails, $request)
    {
        try {
            $this->project->update($id, $projectDetails);
            $this->documentLink->update($id, $request);
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

    public function findDocumentLinkByProjectId($projectId)
    {
        $documentLinks = $this->documentLink->findByProjectId($projectId);

        return $this->dbDocumentLinkFormat($documentLinks);
    }

    public function getParticipatingOrganizations($id, $orgType)
    {
        return $this->project->getParticipatingOrganizations($id, $orgType);
    }

    public function searchData($data)
    {
        $this->project->searchData($data);
    }

    public function getProjectData($projectId = null)
    {
        return $this->project->getProjectData($projectId);
    }

    public function getJsonData($projects)
    {
        $regionName = [];
        $startDate  = "";
        $endDate    = "";
        $jsonData = [];

        if($projects instanceof Collection){
            foreach ($projects as $index => $data){
                if($data->activity_date != null) {
                    foreach ($data->activity_date as $activityDate) {
                        if ($activityDate['type'] == 2) {
                            $startDate = $activityDate['date'];
                        } elseif ($activityDate['type'] == 4) {
                            $endDate = $activityDate['date'];
                        }
                    }
                }

                if($data->location != null) {
                    $i = 0;
                    foreach ($data->location as $location) {
                        foreach ($location['administrative'] as $index => $administrative) {
                            if ($index == 0) {
                                $regionName[$i] = $administrative['code'];
                                $i ++;
                            }

                        }
                    }
                }

                $jsonData[$index] = [
                    'id'         => $data->id,
                    'identifier' => $data->identifier['activity_identifier'],
                    'title'      => $data->title[0]['narrative'],
                    'sectors'    => [$data->sector[0]['sector_category_code']],
                    'regions'    => $regionName,
                    'startdate'  => $startDate,
                    'enddate'    => $endDate
                ];
            }
        }else{
            foreach ($projects->activity_date as $activityDate) {
                if ($activityDate['type'] == 2) {
                    $startDate = $activityDate['date'];
                } elseif ($activityDate['type'] == 4) {
                    $endDate = $activityDate['date'];
                }
            }

            $i = 0;
            foreach ($projects->location as $location) {
                foreach ($location['administrative'] as $index => $administrative) {
                    if ($index == 0) {
                        $regionName[$i] = $administrative['code'];
                        $i ++;
                    }

                }
            }

            $jsonData[0] = [
                'id'         => $projects->id,
                'identifier' => $projects->identifier['activity_identifier'],
                'title'      => $projects->title[0]['narrative'],
                'sectors'    => [$projects->sector[0]['sector_category_code']],
                'regions'    => $regionName,
                'startdate'  => $startDate,
                'enddate'    => $endDate
            ];
        }

        return $jsonData;
    }
    
    public function getProjectsByOrganisationId($orgId)
    {
        return $this->project->getProjectsByOrganisationId($orgId);
    }
}
