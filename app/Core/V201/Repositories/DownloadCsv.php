<?php namespace App\Core\V201\Repositories;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityResult;
use App\Models\Activity\Transaction;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DownloadCsv
 * @package App\Core\V201\Repositories
 */
class DownloadCsv
{
    /**
     * @var Activity
     */
    protected $activity;
    /**
     * @var Transaction
     */
    protected $transaction;
    /**
     * @var ActivityResult
     */
    protected $result;

    /**
     * @param Activity       $activity
     * @param Transaction    $transaction
     * @param ActivityResult $result
     */
    function __construct(Activity $activity, Transaction $transaction, ActivityResult $result)
    {
        $this->activity    = $activity;
        $this->transaction = $transaction;
        $this->result      = $result;
    }

    /**
     * get all activities
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllActivities()
    {
        return $this->activity->all();
    }

    /**
     * get all transactions of an activity
     * @param $activityId
     * @return mixed
     */
    public function getActivityTransactions($activityId)
    {
        return $this->transaction->where('activity_id', $activityId)->get();
    }

    /**
     * get all activity of an activity
     * @param $activityId
     * @return mixed
     */
    public function getActivityResult($activityId)
    {
        return $this->result->where('activity_id', $activityId)->get();
    }

    /**
     * Get data for the Simple CSV to be generated.
     * @param $organizationId
     * @return Collection|static[]
     */
    public function simpleCsvData($organizationId)
    {
        return $this->activity->with(['transactions'])->where('organization_id', '=', $organizationId)->get();
    }

    /**
     * Get data for the Complete CSV to be generated.
     * @param $organizationId
     * @return Collection|static[]
     */
    public function completeCsvData($organizationId)
    {
        return $this->activity->with(['organization', 'results', 'transactions'])->where('organization_id', '=', $organizationId)->get();
    }
}
