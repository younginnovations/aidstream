<?php
namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;

/**
 * Class IatiIdentifierRepository
 * @package App\Core\V201\Repositories\Activity
 */
class IatiIdentifierRepository
{
    /**
     * @var Activity
     */
    protected $activity;

    /**
     * IatiIdentifierRepository constructor.
     * @param Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * @param $input
     * @param $activity
     */
    public function update(array $input, Activity $activity)
    {
        unset($input['_token']);
        unset($input['_method']);
        unset($input['id']);
        $activity->identifier = $input;
        $activity->save();
    }

    /**
     * @param $activityId
     * @return model
     */
    public function getIatiIdentifierData($activityId)
    {
        return $this->activity->findorFail($activityId)->identifier;
    }

    /**
     * @param $activityId
     * @return mixed
     */
    public function getActivityData($activityId)
    {
        return $this->activity->findorFail($activityId);

    }

    /**
     * get all activity identifiers
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getActivityIdentifiers()
    {
        return $this->activity->all('identifier');
    }

    /**
     * get activity identifier except activity ids
     * @param $activityId
     * @return mixed
     */
    public function getActivityIdentifiersExcept($activityId)
    {
        return $this->activity->where('id', '<>', $activityId)->get(['identifier']);
    }

    /**
     * get identifier of an organization
     * @return mixed
     */
    public function getIdentifiersForOrganization()
    {
        return $this->activity->where('organization_id', '=', session('org_id'))->get(['identifier']);
    }

    /**
     * get identifier except activityId of an organization
     * @param $activityId
     * @return mixed
     */
    public function getActivityIdentifiersForOrganizationExcept($activityId)
    {
        return $this->activity->where(
            function ($query) use ($activityId) {
                $query->where('id', '<>', $activityId)->where('organization_id', '=', session('org_id'));
            }
        )->get(['identifier']);
    }
}
