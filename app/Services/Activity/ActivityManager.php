<?php
namespace app\Services\Activity;

use App\Core\Version;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;

class ActivityManager
{
    protected $repo;
    /**
     * @var Guard
     */
    private $auth;
    /**
     * @var Log
     */
    private $log;
    /**
     * @var Version
     */
    private $version;

    /**
     * @param Version $version
     * @param Log     $log
     * @param Guard   $auth
     */
    public function __construct(Version $version, Log $log, Guard $auth)
    {
        $this->repo    = $version->getActivityElement()->getRepository();
        $this->auth    = $auth;
        $this->log     = $log;
        $this->version = $version;
    }

    /**
     * write brief description
     * @param array $input
     * @param       $organizationId
     * @return bool
     */
    public function store(array $input, $organizationId)
    {
        try {
            $result = $this->repo->store($input, $organizationId);
            $this->log->info(
                'Activity identifier added',
                ['for ' => $input['activity_identifier']]
            );
            $this->log->activity(
                "activity.added",
                [
                    'identifier'      => $input['activity_identifier'],
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id,
                ]
            );

            return $result;
        } catch (Exception $exception) {
            $this->log->error(
                sprintf('Activity identifier couldn\'t be added due to %s', $exception->getMessage()),
                [
                    'ActivityIdentifier' => $input,
                    'trace'              => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * write brief description
     * @param $organizationId
     * @return modal
     */
    public function getActivities($organizationId)
    {
        return $this->repo->getActivities($organizationId);
    }

}
