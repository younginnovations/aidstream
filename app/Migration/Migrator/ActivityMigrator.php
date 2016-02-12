<?php namespace App\Migration\Migrator;

use App\Migration\Entities\Activity;
use App\Models\Activity\Activity as ActivityModel;
use App\Migration\Migrator\Contract\MigratorContract;


/**
 * Class ActivityMigrator
 * @package App\Migration\Migrator
 */
class ActivityMigrator implements MigratorContract
{
    /**
     * @var Activity
     */
    protected $activity;

    /**
     * @var ActivityModel
     */
    protected $activityModel;

    /**
     * ActivityMigrator constructor.
     * @param Activity      $activity
     * @param ActivityModel $activityModel
     */
    public function __construct(Activity $activity, ActivityModel $activityModel)
    {
        $this->activity      = $activity;
        $this->activityModel = $activityModel;
    }

    /**
     * {@inheritdoc}
     */
    public function migrate(array $accountIds)
    {
        $orgActivityDetails = $this->activity->getData($accountIds);

        foreach ($orgActivityDetails as $activityDetail) {
            foreach ($activityDetail as $detail) {
                $activity = $this->activityModel->newInstance($detail);

                if (!$activity->save()) {
                    return 'Error during Activity table migration.';
                }
            }
        }

        return 'Activities table migrated.';
    }
}
