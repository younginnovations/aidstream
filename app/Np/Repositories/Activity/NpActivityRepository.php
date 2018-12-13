<?php namespace App\Np\Repositories\Activity;

use App\Np\Contracts\NpActivityRepositoryInterface;
use App\Models\Activity\Activity;
use App\Models\Activity\ActivityLocation;

/**
 * Class ActivityRepository
 * @package App\Np\Repositories\NpActivity
 */
class NpActivityRepository implements NpActivityRepositoryInterface
{
    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @var ActivityLocation
     */
    protected $activityLocation;

    /**
     * ActivityRepository constructor.
     * @param Activity $activity
     * @param ActivityLocation $activityLocation
     */
    public function __construct(Activity $activity , ActivityLocation $activityLocation)
    {
        $this->activity         = $activity;
        $this->activityLocation = $activityLocation;
    }

    /**
     * Get all the activities of the current municipality.
     *
     * @param $municipalityId
     * @return Array $activity
     */
    public function allActivities($municipality_id)
    {
        $activity_ids = $this->activityLocation->where('municipality_id', $municipality_id)->distinct()->pluck('activity_id');

        foreach($activity_ids as $key => $activity_id){
            $activities[] = $this->find($activity_id);
        }

        return $activities;
    }

    /**
     * Returns all the activities of an organization.
     *
     * @param $organizationId
     * @return mixed
     */
    public function all($organizationId)
    {
        return $this->activity->where('organization_id', '=', $organizationId)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return $this->activity->findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $data)
    {
        $data['organization_id'] = session('org_id');

        return $this->activity->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($activityId)
    {
        $activity = $this->find($activityId);

        return $activity->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function update($activityId, array $data)
    {
        $activity                = $this->find($activityId);
        $data['organization_id'] = session('org_id');
        $activity                = $this->resetWorkflow($activity->fill($data));

        return $activity->save();
    }

    /**
     * Reset the Activity Workflow.
     *
     * @param Activity $activity
     * @return Activity
     */
    public function resetWorkflow(Activity $activity)
    {
        $activity->activity_workflow = 0;

        return $activity;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteBudget($activityId, $index)
    {
        $activity = $this->find($activityId);
        $budget   = $activity->budget;

        unset($budget[$index]);

        $activity->budget = array_values($budget);
        $this->resetWorkflow($activity);

        $activity->save();
    }
}
