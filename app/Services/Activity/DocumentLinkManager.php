<?php namespace App\Services\Activity;

use App\Core\Version;
use App\Models\Activity\ActivityDocumentLink;
use App\Services\DocumentManager;
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
    public function __construct(DocumentManager $documentManager, Version $version, Guard $auth, DatabaseManager $database, Log $dbLogger, LoggerInterface $logger)
    {
        $this->auth             = $auth;
        $this->database         = $database;
        $this->DocumentLinkRepo = $version->getActivityElement()->getDocumentLink()->getRepository();
        $this->dbLogger         = $dbLogger;
        $this->logger           = $logger;
        $this->documentManager  = $documentManager;
    }

    /**
     * updates Activity Document Link
     * @param array                $documentLink
     * @param ActivityDocumentLink $activityDocumentLink
     * @return bool
     */
    public function update(array $documentLink, ActivityDocumentLink $activityDocumentLink)
    {
        try {
            $this->database->beginTransaction();
            $documentLinkExists = $activityDocumentLink->exists;
            $activityId         = $activityDocumentLink->activity_id;
            if ($documentLinkExists) {
                $url        = $activityDocumentLink->document_link['url'];
                $document   = $this->documentManager->getDocument(session('org_id'), $url);
                $activities = (array) $document->activities;
                unset($activities[$activityId]);
                $document->activities = $activities;
                $this->documentManager->update($document);
            }

            $url                     = $documentLink[0]['url'];
            $document                = $this->documentManager->getDocument(session('org_id'), $url);
            $activities              = (array) $document->activities;
            $identifier              = $activityDocumentLink->activity->identifier['activity_identifier'];
            $activities[$activityId] = $identifier;
            $document->activities    = $activities;
            $this->documentManager->update($document);

            $this->DocumentLinkRepo->update($documentLink, $activityDocumentLink);
            $this->database->commit();
            $this->logger->info(
                sprintf('Activity Document Link %s!', $documentLinkExists ? 'updated' : 'saved'),
                ['for' => $documentLink]
            );
            $this->dbLogger->activity(
                sprintf("activity.document_link_%s", $documentLinkExists ? 'updated' : 'saved'),
                [
                    'activity_id'      => $activityDocumentLink->activity_id,
                    'document_link_id' => $activityDocumentLink->id,
                    'organization'     => $this->auth->user()->organization->name,
                    'organization_id'  => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (\Exception $exception) {
            $this->database->rollback();
            $this->logger->error($exception, ['documentLink' => $documentLink]);
        }

        return false;
    }

    /**
     * Get Document Link Data.
     * @param $documentLinkId
     * @param $activityId
     * @return ActivityDocumentLink
     */
    public function getDocumentLink($documentLinkId, $activityId)
    {
        return $this->DocumentLinkRepo->getDocumentLink($documentLinkId, $activityId);
    }

    /**
     * @param $activityId
     * @return mixed
     */
    public function getDocumentLinks($activityId)
    {
        return $this->DocumentLinkRepo->getDocumentLinks($activityId);
    }

    /**
     * @param ActivityDocumentLink $activityDocumentLink
     * @return bool
     */
    public function delete(ActivityDocumentLink $activityDocumentLink)
    {
        try {
            $this->DocumentLinkRepo->delete($activityDocumentLink);

            $this->dbLogger->activity(
                "activity.activity_document_link_deleted",
                [
                    'document_link_id' => $activityDocumentLink->id,
                    'activity_id'      => $activityDocumentLink->activity_id
                ],
                $activityDocumentLink->toArray()
            );

            return true;
        } catch (\Exception $exception) {
            $this->logger->error($exception, ['documentLink' => $activityDocumentLink]);
        }

        return false;
    }
}
