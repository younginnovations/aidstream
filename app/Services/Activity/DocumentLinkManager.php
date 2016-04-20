<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\Activity;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Psr\Log\LoggerInterface;
use Illuminate\Database\DatabaseManager;

/**
 * Class DocumentLinkManager
 * @package App\Services\Activity
 */
class DocumentLinkManager
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
     * @var DatabaseManager
     */
    protected $database;
    /**
     * @var Log
     */
    protected $dbLogger;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Version         $version
     * @param Guard           $auth
     * @param DatabaseManager $database
     * @param Log             $dbLogger
     * @param LoggerInterface $logger
     */
    public function __construct(Version $version, Guard $auth, DatabaseManager $database, Log $dbLogger, LoggerInterface $logger)
    {
        $this->auth             = $auth;
        $this->database         = $database;
        $this->DocumentLinkRepo = $version->getActivityElement()->getDocumentLink()->getRepository();
        $this->dbLogger         = $dbLogger;
        $this->logger           = $logger;
    }

    /**
     * updates Activity Document Link
     * @param array    $activityDetails
     * @param Activity $activity
     * @return bool
     */
    public function update(array $activityDetails, Activity $activity)
    {
        try {
            $this->database->beginTransaction();
            $this->DocumentLinkRepo->update($activityDetails, $activity);
            $this->database->commit();
            $this->logger->info(
                'Activity Document Link updated!',
                ['for' => $activity->document_link]
            );
            $this->dbLogger->activity(
                "activity.document_link",
                [
                    'activity_id'     => $activity->id,
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (\Exception $exception) {
            $this->database->rollback();
            $this->logger->error($exception, ['documentLink' => $activityDetails]);
        }

        return false;
    }

    /**
     * Get Document Link Data.
     * @param $id
     * @return array|null
     */
    public function getDocumentLinkData($id)
    {
        return $this->DocumentLinkRepo->getDocumentLinkData($id);
    }
}
