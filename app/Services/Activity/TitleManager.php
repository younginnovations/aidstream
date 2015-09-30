<?php namespace app\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;

/**
 * Class TitleManager
 * Contains the function that will update the activity title and returns the activity data.
 * @package app\Services\Activity
 */
class TitleManager
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

    /**
     * @param Version $version
     * @param Log     $log
     * @param Guard   $auth
     */
    public function __construct(Version $version, Log $log, Guard $auth)
    {
        $this->auth          = $auth;
        $this->log           = $log;
        $this->version       = $version;
        $this->iatiTitleRepo = $version->getActivityElement()->getTitle()->getRepository();
    }

    /**
     * updates Activity Title
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->iatiTitleRepo->update($activityDetails, $activity);
            $this->log->info(
                'Activity Title Updated!',
                ['for ' => $activity['narrative']]
            );
            $this->log->activity(
                "activity.title_updated",
                [
                    'title'           => $activityDetails['narrative'],
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->log->error(
                sprintf('Activity Title could not be updated due to %s', $exception->getMessage()),
                [
                    'Title' => $activityDetails,
                    'trace' => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }

    /**
     * @param $id
     * @return model
     */
    public function getTitleData($id)
    {
        return $this->iatiTitleRepo->getTitleData($id);
    }

    /**
     * @param $id
     * @return model
     */
    public function getActivityData($id)
    {
        return $this->iatiTitleRepo->getActivityData($id);

    }
}
