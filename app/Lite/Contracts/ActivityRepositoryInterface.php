<?php namespace App\Lite\Contracts;

use App\Models\Activity\Activity;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ActivityRepositoryInterface
 * @package App\Lite\Contracts
 */
interface ActivityRepositoryInterface
{
    /**
     * Get all the activities of the current Organization.
     *
     * @param $organizationId
     * @return Collection
     */
    public function all($organizationId);

    /**
     * Find an Activity by its id.
     *
     * @param $id
     * @return Activity
     */
    public function find($id);

    /**
     * Save the Activity data into the database.
     *
     * @param array $data
     * @return Activity
     */
    public function save(array $data);

    /**
     * @param $activityId
     * @return bool|null
     */
    public function delete($activityId);

    /**
     * @param       $activityId
     * @param array $activity
     * @return bool
     */
    public function update($activityId, array $activity);

    /**
     * Delete a specific Budget from an Activity.
     *
     * @param $activityId
     * @param $get
     * @return mixed
     */
    public function deleteBudget($activityId, $get);
}
