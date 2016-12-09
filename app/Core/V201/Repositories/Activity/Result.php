<?php namespace App\Core\V201\Repositories\Activity;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityResult;

/**
 * Class Result
 * @package App\Core\V201\Repositories\Activity
 */
class Result
{
    /**
     * @var Activity
     */
    protected $activity;
    /**
     * @var ActivityResult
     */
    protected $activityResult;

    /**
     * @param Activity       $activity
     * @param ActivityResult $activityResult
     */
    function __construct(Activity $activity, ActivityResult $activityResult)
    {
        $this->activity       = $activity;
        $this->activityResult = $activityResult;
    }

    /**
     * Create a new ActivityResult.
     *
     * @param array $resultData
     * @return ActivityResult
     */
    public function create(array $resultData)
    {
        return $this->activityResult->create($resultData);
    }

    /**
     * Store Activity Result
     * @param array          $resultData
     * @param ActivityResult $activityResult
     * @return bool
     */
    public function update(array $resultData, ActivityResult $activityResult)
    {
        $activityResult->result = $resultData['result'][0];

        return $activityResult->save();
    }

    /**
     * Return Activity Results
     * @param $activityId
     * @return collection
     */
    public function getResults($activityId)
    {
        return $this->activityResult->where('activity_id', $activityId)->get();
    }

    /**
     * Return specific result
     * @param $id
     * @param $activityId
     * @return Model
     */
    public function getResult($id, $activityId)
    {
        return $this->activityResult->firstOrNew(['id' => $id, 'activity_id' => $activityId]);
    }

    /**
     * Delete specific activity result
     * @param ActivityResult $activityResult
     * @return bool|null
     * @throws \Exception
     */
    public function deleteResult(ActivityResult $activityResult)
    {
        return $activityResult->delete();
    }

    public function xmlResult($result, $activityId)
    {
        return $this->activityResult->create(['result' => $result['result'], 'activity_id' => $activityId]);
    }
}
