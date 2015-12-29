<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as Logger;
use Illuminate\Database\DatabaseManager;

/**
 * Class CountryBudgetItemManager
 * @package App\Services\Activity
 */
class CountryBudgetItemManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Version
     */
    protected $version;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @param Version         $version
     * @param Guard           $auth
     * @param DatabaseManager $database
     * @param Logger          $logger
     */
    public function __construct(Version $version, Guard $auth, DatabaseManager $database, Logger $logger)
    {
        $this->auth                  = $auth;
        $this->logger                = $logger;
        $this->database              = $database;
        $this->CountryBudgetItemRepo = $version->getActivityElement()->getCountryBudgetItem()->getRepository();
    }

    /**
     * updates Activity Country Budget Items
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->database->beginTransaction();
            $this->CountryBudgetItemRepo->update($activityDetails, $activity);
            $this->database->commit();
            $this->logger->info(
                'Activity Country Budget Items updated!',
                ['for' => $activity->country_budget_items]
            );
            $this->logger->activity(
                "activity.country_budget_items",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                sprintf('Activity Country Budget Items could not be updated due to %s', $exception->getMessage()),
                [
                    'countryBudgetItems' => $activityDetails,
                    'trace'              => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return model
     */
    public function getCountryBudgetItemData($id)
    {
        return $this->CountryBudgetItemRepo->getCountryBudgetItemData($id);
    }
}
