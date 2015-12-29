<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;

class IatiIdentifierManager
{
    protected $repo;
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

    /**
     * @param Version $version
     * @param Log     $log
     * @param Guard   $auth
     */
    public function __construct(Version $version, Log $log, Guard $auth)
    {
        $this->repo               = $version->getActivityElement()->getRepository();
        $this->auth               = $auth;
        $this->log                = $log;
        $this->version            = $version;
        $this->iatiIdentifierRepo = $version->getActivityElement()->getIdentifier()->getRepository();
    }

    /**
     * @param array    $input
     * @param Activity $activity
     * @return bool
     * @internal param $organization
     */
    public function update(array $input, Activity $activity)
    {
        try {
            $this->iatiIdentifierRepo->update($input, $activity);
            $this->log->info(
                'Activity Iati Identifier updates',
                ['for ' => $activity['activity_identifier']]
            );
            $this->log->activity(
                "activity.iati_identifier_updated",
                [
                    'identifier'      => $input['activity_identifier'],
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {

            $this->log->error(
                sprintf('Iati identifier could not be updated due to %s', $exception->getMessage()),
                [
                    'IatiIdentifier' => $input,
                    'trace'          => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return model
     */
    public function getIatiIdentifierData($id)
    {
        return $this->iatiIdentifierRepo->getIatiIdentifierData($id);
    }

    /**
     * @param $id
     * @return model
     */
    public function getActivityData($id)
    {
        return $this->iatiIdentifierRepo->getActivityData($id);

    }
}
