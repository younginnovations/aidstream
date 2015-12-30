<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Contracts\Auth\Guard;
use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Logging\Log;

/**
 * Class LegacyDataManager
 * @package App\Services\Activity
 */
class LegacyDataManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Log
     */
    protected $dbLogger;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Version
     */
    protected $version;


    /**
     * @param Version         $version
     * @param Log             $dbLogger
     * @param Guard           $auth
     * @param LoggerInterface $logger
     */
    public function __construct(Version $version, Log $dbLogger, Guard $auth, LoggerInterface $logger)
    {
        $this->auth                 = $auth;
        $this->dbLogger             = $dbLogger;
        $this->logger               = $logger;
        $this->iatiLegacyRepo = $version->getActivityElement()->getLegacyData()->getRepository();
    }

    /**
     * updates Activity Date
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->iatiLegacyRepo->update($activityDetails, $activity);
            $this->logger->info(
                'Legacy Data Updated!',
                ['for' => $activity->legacy_data]
            );
            $this->dbLogger->activity(
                "activity.legacy_data_updated",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Legacy Data could not be updated due to %s', $exception->getMessage()),
                [
                    'legacyData' => $activityDetails,
                    'trace'      => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getLegacyData($id)
    {
        return $this->iatiLegacyRepo->getLegacyData($id);
    }
}
