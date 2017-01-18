<?php namespace App\Services\XmlImporter\Foundation;


use App\Core\V201\Repositories\Activity\ActivityRepository;
use App\Core\V201\Repositories\Activity\DocumentLink;
use App\Core\V201\Repositories\Activity\Result;
use App\Core\V201\Repositories\Activity\Transaction;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlServiceProvider;

/**
 * Class XmlQueueProcessor
 * @package App\Services\XmlImporter\Foundation
 */
class XmlQueueProcessor
{
    /**
     * @var XmlServiceProvider
     */
    protected $xmlServiceProvider;
    /**
     * @var XmlProcessor
     */
    protected $xmlProcessor;
    /**
     *
     */
    const UPLOADED_XML_STORAGE_PATH = 'xmlImporter/tmp/file';
    /**
     * @var
     */
    protected $userId;
    /**
     * @var ActivityRepository
     */
    protected $activityRepo;
    /**
     * @var Transaction
     */
    protected $transactionRepo;
    /**
     * @var Result
     */
    protected $resultRepo;
    /**
     * @var DocumentLink
     */
    protected $documentLinkRepo;

    /**
     * @var
     */
    protected $orgId;

    /**
     * @var
     */
    protected $filename;


    /**
     * @var array
     */
    protected $jsonData = [];

    /**
     * XmlQueueProcessor constructor.
     * @param XmlServiceProvider $xmlServiceProvider
     * @param XmlProcessor       $xmlProcessor
     * @param ActivityRepository $activityRepo
     * @param Transaction        $transactionRepo
     * @param Result             $resultRepo
     * @param DocumentLink       $documentLinkRepo
     */
    public function __construct(
        XmlServiceProvider $xmlServiceProvider,
        XmlProcessor $xmlProcessor,
        ActivityRepository $activityRepo,
        Transaction $transactionRepo,
        Result $resultRepo,
        DocumentLink $documentLinkRepo
    ) {
        $this->xmlServiceProvider = $xmlServiceProvider;
        $this->xmlProcessor       = $xmlProcessor;
        $this->activityRepo       = $activityRepo;
        $this->transactionRepo    = $transactionRepo;
        $this->resultRepo         = $resultRepo;
        $this->documentLinkRepo   = $documentLinkRepo;
    }

    /**
     * Import the Xml data.
     *
     * @param $filename
     * @param $orgId
     * @param $userId
     * @return bool|null
     */
    public function import($filename, $orgId, $userId)
    {
        $this->orgId    = $orgId;
        $this->userId   = $userId;
        $this->filename = $filename;
        $file           = $this->temporaryXmlStorage($filename);

        $contents = file_get_contents($file);
        if ($this->xmlServiceProvider->isValidAgainstSchema($contents)) {
            $version          = $this->xmlServiceProvider->version($contents);
            $xmlData          = $this->xmlServiceProvider->load($contents);
            $mappedActivities = $this->xmlProcessor->process($xmlData, $version);
            if ($mappedActivities) {
                $this->save($mappedActivities, $orgId);
            }
        } else {
            shell_exec(sprintf('chmod 777 -R %s', $this->temporaryXmlStorage()));

            $this->storeInJsonFile('schema_error.json', ['filename' => $filename, 'version' => $this->xmlServiceProvider->version($contents, true)]);
        }

        return false;
    }

    /**
     *  Store mapped activity in database.
     * @param $mappedActivities
     * @param $orgId
     * @return bool
     */
    protected function save($mappedActivities, $orgId)
    {
        $success = 0;
        $failed  = 0;
        foreach ($mappedActivities as $index => $activity) {
            $iatiIdentifierText = getVal($activity, ['identifier', 'iati_identifier_text']);
            $dbActivities       = $this->dbActivities();
            if ($this->isIatiIdentifierDifferent($dbActivities, $iatiIdentifierText)) {
                $activity['organization_id']   = $orgId;
                $activity['imported_from_xml'] = 1;
                $storeActivity                 = $this->activityRepo->importXmlActivities($activity, $orgId);
                $activityId                    = $storeActivity->id;
                $success ++;

                $this->saveTransactions($activity, $activityId)
                     ->saveResults($activity, $activityId)
                     ->saveDocumentLink($activity, $activityId);
            } else {
                $failed ++;
                $this->storeInvalidActivity($activity, $index);
            }
            $this->storeXmlImportStatus(count($mappedActivities), $index + 1, $success, $failed);
        }

        return true;
    }

    /**
     *  Save transaction of mapped activity in database.
     * @param $activity
     * @param $activityId
     * @return $this
     */
    protected function saveTransactions($activity, $activityId)
    {
        foreach (getVal($activity, ['transactions'], []) as $transaction) {
            $this->transactionRepo->createTransaction($transaction, $activityId);
        }

        return $this;
    }

    /**
     *  Save result of mapped activity in database
     * @param $activity
     * @param $activityId
     * @return $this
     */
    protected function saveResults($activity, $activityId)
    {
        foreach (getVal($activity, ['result'], []) as $result) {
            $resultData['result'] = $result;
            $this->resultRepo->xmlResult($resultData, $activityId);
        }

        return $this;
    }

    /**
     *  Save document link of mapped activity in database.
     * @param $activity
     * @param $activityId
     * @return $this
     */
    protected function saveDocumentLink($activity, $activityId)
    {
        foreach (getVal($activity, ['document_link'], []) as $documentLink) {
            $documentLinkData['document_link'] = $documentLink;
            $this->documentLinkRepo->xmlDocumentLink($documentLinkData, $activityId);
        }

        return $this;
    }

    /**
     * Get the temporary storage path for the uploaded Xml file.
     *
     * @param null $filename
     * @return string
     */
    protected function temporaryXmlStorage($filename = null)
    {
        if ($filename) {
            return sprintf('%s/%s', storage_path(sprintf('%s/%s/%s', self::UPLOADED_XML_STORAGE_PATH, $this->orgId, $this->userId)), $filename);
        }

        return storage_path(sprintf('%s/%s/%s/', self::UPLOADED_XML_STORAGE_PATH, $this->orgId, $this->userId));
    }

    /**
     * Returns activities of the organisation.
     * @return \App\Core\V201\Repositories\Activity\modal
     */
    protected function dbActivities()
    {
        return $this->activityRepo->getActivities($this->orgId);
    }

    /**
     *  Check if the iati identifier text is similar to the identifier of imported xml file.
     * @param $activities
     * @param $xmlIdentifier
     * @return bool
     */
    protected function isIatiIdentifierDifferent($activities, $xmlIdentifier)
    {
        foreach ($activities as $activity) {
            if (($xmlIdentifier == getVal($activity->identifier, ['iati_identifier_text'])) || $xmlIdentifier == "") {
                return false;
            }
        }

        return true;
    }

    /**
     * Store status of completed xml file.
     * @param $totalActivities
     * @param $currentActivity
     * @param $success
     * @param $failed
     */
    protected function storeXmlImportStatus($totalActivities, $currentActivity, $success, $failed)
    {
        shell_exec(sprintf('chmod 777 -R %s', $this->temporaryXmlStorage()));
        $data = ['total_activities' => $totalActivities, 'current_activity_count' => $currentActivity, 'success' => $success, 'failed' => $failed];
        $this->storeInJsonFile('xml_completed_status.json', $data);
    }

    /**
     * Store Activities having same identifier in a json file.
     * @param $activity
     * @param $index
     */
    protected function storeInvalidActivity($activity, $index)
    {
        $this->jsonData[$index] = $activity;
        $this->storeInJsonFile('xml_invalid.json', $this->jsonData);
    }

    /**
     * Store data in given json filename
     * @param $filename
     * @param $data
     */
    protected function storeInJsonFile($filename, $data)
    {
        $filePath = $this->temporaryXmlStorage($filename);
        file_put_contents($filePath, json_encode($data));
    }
}
