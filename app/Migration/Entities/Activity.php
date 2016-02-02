<?php namespace App\Migration\Entities;

use App\Migration\MigrateActivity;

/**
 * Class Activity
 * @package App\Migration\Entities
 */
class Activity
{
    /**
     * @var MigrateActivity
     */
    protected $activityData;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Activity constructor.
     * @param MigrateActivity $activityData
     */
    public function __construct(MigrateActivity $activityData)
    {
        $this->activityData     = $activityData;
    }


    /**
     * Gets Activities data from old database.
     * @return array
     */
    public function getData()
    {
        $orgId = ['2', '100', '9']; // get Organization Ids.

        foreach ($orgId as $id) {
            $this->data[] = $this->activityData->fetchActivityData($id);
        }

        return $this->data;
    }
}
