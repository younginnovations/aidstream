<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LocationManager
 * @package App\Services\Activity
 */
class LocationManager
{
    /**
     * @var Guard
     */
    protected $auth;
    /**
     * @var Log
     */
    protected $log;
    /**
     * @var Version
     */
    protected $version;
    protected $locationRepo;

    /**
     * @param Version $version
     * @param Log     $log
     * @param Guard   $auth
     */
    function __construct(Version $version, Log $log, Guard $auth)
    {
        $this->locationRepo = $version->getActivityElement()->getLocation()->getRepository();
        $this->auth         = $auth;
        $this->log          = $log;
    }

    /**
     * update location
     * @param array    $input
     * @param Activity $activity
     * @return bool
     */
    public function update(array $input, Activity $activity)
    {
        try {
            $this->locationRepo->update($input, $activity);
            $this->log->info(
                'Activity Location Updated!',
                ['for' => $activity->location]
            );
            $this->log->activity(
                "activity.location_updated",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->log->error(
                sprintf('Location could not be updated due to %s', $exception->getMessage()),
                [
                    'Location' => $input,
                    'trace'    => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return Model
     */
    public function getLocation($id)
    {
        return $this->locationRepo->getLocation($id);
    }

}
