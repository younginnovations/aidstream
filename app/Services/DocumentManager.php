<?php namespace App\Services;

use App\Core\Version;
use App\Models\Document;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class DocumentManager
 * @package App\Services
 */
class DocumentManager
{
    /**
     * @var
     */
    protected $repo;
    /**
     * @var Version
     */
    private $version;
    /**
     * @var Guard
     */
    private $auth;
    /**
     * @var DatabaseManager
     */
    private $database;
    /**
     * @var DbLogger
     */
    private $dbLogger;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param Version         $version
     * @param Guard           $auth
     * @param DatabaseManager $database
     * @param DbLogger        $dbLogger
     * @param Logger          $logger
     */
    function __construct(Version $version, Guard $auth, DatabaseManager $database, DbLogger $dbLogger, Logger $logger)
    {
        $this->repo     = $version->getSettingsElement()->getDocumentRepository();
        $this->version  = $version;
        $this->auth     = $auth;
        $this->database = $database;
        $this->dbLogger = $dbLogger;
        $this->logger   = $logger;
    }

    /**
     * return organization documents
     * @param $orgId
     * @return mixed
     */
    public function getDocuments($orgId)
    {
        return $this->repo->getDocuments($orgId);
    }

    /**
     * return particular document
     * @param      $orgId
     * @param      $url
     * @param null $filename
     * @return mixed
     */
    public function getDocument($orgId, $url, $filename = null)
    {
        return $this->repo->getDocument($orgId, $url, $filename);
    }

    /**
     * save document
     * @param Document $document
     * @return mixed
     */
    public function store(Document $document)
    {
        try {
            $this->database->beginTransaction();
            $this->repo->store($document);
            $this->database->commit();
            $this->logger->info(
                'Document saved successfully.',
                ['for' => $this->auth->user()->organization->id]
            );
            $this->dbLogger->activity(
                "activity.document_saved",
                [
                    'organization'    => $this->auth->user()->organization->name,
                    'organization_id' => $this->auth->user()->organization->id
                ]
            );

            return true;
        } catch (\Exception $exception) {
            $this->database->rollback();
            $this->logger->error(
                'Failed to save document.',
                [
                    'version' => $document,
                    'trace'   => $exception->getTraceAsString()
                ]
            );
        }

        return false;
    }
}
