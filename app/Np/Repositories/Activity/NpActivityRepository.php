<?php namespace App\Np\Repositories\Activity;

use App\Np\Contracts\NpActivityRepositoryInterface;
use App\Models\Activity\Activity;
use Auth;
use DB;

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

    public function listAll()
    {
        $organization = DB::table('organization_location')->where('municipality_id','=',Auth::User()->getMunicipalityByAdmin())->first();
        $activityMunicipality = DB::table('activity_location')->where('municipality_id', '=', Auth::User()->getMunicipalityByAdmin())->get(['activity_id']);

        $activityList =  $this->activity
                    ->where('organization_id', '=', $organization->organization_id)
                    // ->join('activity_location','activity_data.id','=','activity_location.activity_id')
                    // ->join('municipalities','municipalities.id','=','activity_location.municipality_id')
                    ->get();

        return $activityList;
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
