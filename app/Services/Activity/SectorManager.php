<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as Logger;

/**
 * Class SectorManager
 * @package App\Services\Activity
 */
class SectorManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var Version
     */
    protected $version;

    /**
     * @param Version $version
     * @param Logger  $logger
     * @param Guard   $auth
     */
    public function __construct(Version $version, Guard $auth, Logger $logger)
    {
        $this->auth       = $auth;
        $this->logger     = $logger;
        $this->sectorRepo = $version->getActivityElement()->getSector()->getRepository();
    }

    /**
     * updates Activity Sector
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->sectorRepo->update($activityDetails, $activity);
            $this->logger->info(
                'Activity Sector updated!',
                ['for' => $activity->sector]
            );
            $this->logger->activity(
                "activity.sector_updated",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Activity sector could not be updated due to %s', $exception->getMessage()),
                [
                    'sector' => $activityDetails,
                    'trace'  => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return model
     */
    public function getSectorData($id)
    {
        return $this->sectorRepo->getSectorData($id);
    }
}
