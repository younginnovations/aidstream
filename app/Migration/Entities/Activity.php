<?php namespace App\Migration\Entities;

use App\Migration\Migrator\Data\ActivityQuery;

/**
 * Class Activity
 * @package App\Migration\Entities
 */
class Activity
{
    /**
     * @var ActivityQuery
     */
    protected $activityQuery;

    /**
     * Activity constructor.
     * @param ActivityQuery $activityQuery
     */
    public function __construct(ActivityQuery $activityQuery)
    {
        $this->activityQuery = $activityQuery;
    }

    /**
     * Gets Activities data from old database.
     * @param $accountIds
     * @return array
     */
    public function getData($accountIds)
    {
        return $this->activityQuery->executeFor($accountIds);
    }
}
