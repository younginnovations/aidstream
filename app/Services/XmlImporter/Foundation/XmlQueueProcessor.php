<?php namespace App\Services\XmlImporter\Foundation;

use App\Core\V201\Repositories\Activity\ActivityRepository;
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
     * @var
     */
    protected $orgId;

    /**
     * @var
     */
    protected $filename;
    /**
     * @var ActivityRepository
     */
    protected $activityRepo;

    /**
     * XmlQueueProcessor constructor.
     * @param XmlServiceProvider $xmlServiceProvider
     * @param XmlProcessor       $xmlProcessor
     * @param ActivityRepository $activityRepo
     */
    public function __construct(XmlServiceProvider $xmlServiceProvider, XmlProcessor $xmlProcessor, ActivityRepository $activityRepo)
    {
        $this->xmlServiceProvider = $xmlServiceProvider;
        $this->xmlProcessor       = $xmlProcessor;
        $this->activityRepo       = $activityRepo;
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
        $this->orgId       = $orgId;
        $this->userId      = $userId;
        $this->filename    = $filename;
        $file              = $this->temporaryXmlStorage($filename);
        $dbIatiIdentifiers = $this->dbIatiIdentifiers();
        $contents          = file_get_contents($file);

        if (!$this->xmlServiceProvider->allowedVersionOfXml($contents)) {
            $this->storeInJsonFile(
                'error.json',
                [
                    'message' => 'Uploaded xml version is not supported'
                ]
            );

            return false;
        }

        if ($this->xmlServiceProvider->isValidAgainstSchema($contents)) {
            $version = $this->xmlServiceProvider->version($contents);
            $xmlData = $this->xmlServiceProvider->load($contents);
            $this->xmlProcessor->process($xmlData, $version, $userId, $orgId, $dbIatiIdentifiers);

            return true;
        } else {
            shell_exec(sprintf('chmod 777 -R %s', $this->temporaryXmlStorage()));

            $this->storeInJsonFile('schema_error.json', ['filename' => $filename, 'version' => $this->xmlServiceProvider->version($contents, true)]);
        }

        return false;
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
     * Store data in given json filename
     * @param $filename
     * @param $data
     */
    protected function storeInJsonFile($filename, $data)
    {
        $filePath = $this->temporaryXmlStorage($filename);
        file_put_contents($filePath, json_encode($data));
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
     * Returns array of iati identifiers present in the activities of the organisation.
     *
     * @return array
     */
    protected function dbIatiIdentifiers()
    {
        $activities  = $this->dbActivities();
        $identifiers = [];

        foreach ($activities as $index => $activity) {
            $identifiers[] = getVal($activity->identifier, ['iati_identifier_text']);
        }

        return $identifiers;
    }
}

