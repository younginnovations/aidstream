<?php namespace App\Services\XmlImporter\Foundation;


use App\Core\V201\Repositories\Activity\ActivityRepository;
use App\Core\V201\Repositories\Activity\DocumentLink;
use App\Core\V201\Repositories\Activity\Result;
use App\Core\V201\Repositories\Activity\Transaction;

/**
 * Class XmlQueueWriter
 * @package App\Services\XmlImporter\Foundation
 */
class XmlQueueWriter
{
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
     *  Path to xml importer
     */
    const UPLOADED_XML_STORAGE_PATH = 'xmlImporter/tmp/file';
    /**
     * @var
     */
    protected $userId;
    /**
     * @var
     */
    protected $orgId;
    /**
     * @var
     */
    protected $jsonData;
    /**
     * @var int
     */
    protected $success = 0;
    /**
     * @var int
     */
    protected $failed = 0;
    /**
     * @var
     */
    protected $dbIatiIdentifiers;

    /**
     * XmlQueueWriter constructor.
     * @param                    $userId
     * @param                    $orgId
     * @param                    $dbIatiIdentifiers
     * @param ActivityRepository $activityRepo
     * @param Transaction        $transactionRepo
     * @param Result             $resultRepo
     * @param DocumentLink       $documentLinkRepo
     */
    public function __construct($userId, $orgId, $dbIatiIdentifiers, ActivityRepository $activityRepo, Transaction $transactionRepo, Result $resultRepo, DocumentLink $documentLinkRepo)
    {
        $this->activityRepo      = $activityRepo;
        $this->transactionRepo   = $transactionRepo;
        $this->resultRepo        = $resultRepo;
        $this->documentLinkRepo  = $documentLinkRepo;
        $this->userId            = $userId;
        $this->orgId             = $orgId;
        $this->dbIatiIdentifiers = $dbIatiIdentifiers;
    }

    /**
     *  Store mapped activity in database.
     * @param $mappedActivity
     * @param $totalActivities
     * @param $index
     * @return bool
     */
    public function save($mappedActivity, $totalActivities, $index)
    {
        $iatiIdentifierText = getVal($mappedActivity, ['identifier', 'iati_identifier_text']);

        if ($this->isIatiIdentifierDifferent($iatiIdentifierText)) {
            $mappedActivity['organization_id']   = $this->orgId;
            $mappedActivity['imported_from_xml'] = 1;
            $storeActivity                       = $this->activityRepo->importXmlActivities($mappedActivity, $this->orgId);
            $activityId                          = $storeActivity->id;
            $this->success ++;

            $this->saveTransactions($mappedActivity, $activityId)
                 ->saveResults($mappedActivity, $activityId)
                 ->saveDocumentLink($mappedActivity, $activityId);
        } else {
            $this->failed ++;
            $this->storeInvalidActivity($mappedActivity, $index);
        }
        $this->storeXmlImportStatus($totalActivities, $index + 1, $this->success, $this->failed);

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
        $transactionRepo = $this->transactionRepo;
        foreach (getVal($activity, ['transactions'], []) as $transaction) {
            $transactionRepo->createTransaction($transaction, $activityId);
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
        $resultRepo = $this->resultRepo;
        foreach (getVal($activity, ['result'], []) as $result) {
            $resultData['result'] = $result;
            $resultRepo->xmlResult($resultData, $activityId);
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
        $documentLinkRepo = $this->documentLinkRepo;
        foreach (getVal($activity, ['document_link'], []) as $documentLink) {
            $documentLinkData['document_link'] = $documentLink;
            $documentLinkRepo->xmlDocumentLink($documentLinkData, $activityId);
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
     *  Check if the iati identifier text is similar to the identifier of imported xml file.
     * @param $xmlIdentifier
     * @return bool
     */
    protected function isIatiIdentifierDifferent($xmlIdentifier)
    {
        if ($xmlIdentifier == "" || in_array($xmlIdentifier, $this->dbIatiIdentifiers)) {
            return false;
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

