<?php namespace App\Lite\Repositories\Activity;

use App\Lite\Contracts\ActivityRepositoryInterface;
use App\Models\Activity\Activity;

/**
 * Class ActivityRepository
 * @package App\Lite\Repositories\Activity
 */
class ActivityRepository implements ActivityRepositoryInterface
{
    /**
     * @var Activity
     */
    protected $activity;

    /**
     * ActivityRepository constructor.
     * @param Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
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
