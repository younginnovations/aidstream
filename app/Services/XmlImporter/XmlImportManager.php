<?php namespace App\Services\XmlImporter;

use Exception;
use DOMDocument;
use Psr\Log\LoggerInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Filesystem\Filesystem;
use App\Core\V201\Element\Activity\XmlService;
use App\Services\XmlImporter\Events\XmlWasUploaded;
use App\Services\XmlImporter\Foundation\XmlProcessor;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Services\XmlImporter\Foundation\Support\Providers\XmlServiceProvider;

/**
 * Class XmlImportManager
 * @package App\Services\XmlImporter\XmlImportManager
 */
class XmlImportManager
{
    /**
     * Temporary Xml file storage location.
     */
    const UPLOADED_XML_STORAGE_PATH = 'xmlImporter/tmp/file';

    /**
     * @var XmlServiceProvider
     */
    protected $xmlServiceProvider;

    /**
     * @var XmlProcessor
     */
    protected $xmlProcessor;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var XmlService
     */
    protected $xmlService;

    /**
     * XmlImportManager constructor.
     *
     * @param XmlServiceProvider $xmlServiceProvider
     * @param XmlProcessor       $xmlProcessor
     * @param LoggerInterface    $logger
     * @param Filesystem         $filesystem
     * @param XmlService         $xmlService
     */
    public function __construct(
        XmlServiceProvider $xmlServiceProvider,
        XmlProcessor $xmlProcessor,
        LoggerInterface $logger,
        Filesystem $filesystem,
        XmlService $xmlService
    ) {
        $this->xmlServiceProvider = $xmlServiceProvider;
        $this->xmlProcessor       = $xmlProcessor;
        $this->logger             = $logger;
        $this->filesystem         = $filesystem;
        $this->xmlService         = $xmlService;
    }

    /**
     * Temporarily store the uploaded Xml file.
     *
     * @param UploadedFile $file
     * @return bool|null
     */
    public function store(UploadedFile $file)
    {
        try {
            $file->move($this->temporaryXmlStorage(), $file->getClientOriginalName());
            shell_exec(sprintf('chmod 777 -R %s', $this->temporaryXmlStorage()));

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error uploading Xml file due to %s', $exception->getMessage()),
                [
                    'trace' => $exception->getTraceAsString(),
                    'user'  => auth()->user()->id
                ]
            );

            return null;
        }

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
            return sprintf('%s/%s', storage_path(sprintf('%s/%s/%s', self::UPLOADED_XML_STORAGE_PATH, session('org_id'), auth()->user()->id)), $filename);
        }

        return storage_path(sprintf('%s/%s/%s/', self::UPLOADED_XML_STORAGE_PATH, session('org_id'), auth()->user()->id));
    }

    /**
     * Get the id for the current user.
     *
     * @return mixed
     */
    protected function getUserId()
    {
        if (auth()->check()) {
            return auth()->user()->id;
        }
    }

    /**
     * @param $filename
     * @param $userId
     * @param $organizationId
     */
    public function startImport($filename, $userId, $organizationId)
    {
        $this->fireXmlUploadEvent($filename, $userId, $organizationId);
    }

    /**
     * Fire the XmlWasUploaded event.
     *
     * @param $filename
     * @param $userId
     * @param $organizationId
     */
    protected function fireXmlUploadEvent($filename, $userId, $organizationId)
    {
        Event::fire(new XmlWasUploaded($filename, $userId, $organizationId));
    }

    /**
     * Load a json file with a specific filename.
     *
     * @param $filename
     * @return mixed|null
     */
    public function loadJsonFile($filename)
    {
        try {
            $filePath = $this->temporaryXmlStorage($filename);

            if (file_exists($filePath)) {
                return json_decode(file_get_contents($filePath), true);
            }

            return false;
        } catch (Exception $exception) {
            $this->logger->error(
                sprintf('Error due to %s', $exception->getMessage()),
                [
                    'trace'    => $exception->getTraceAsString(),
                    'user_id'  => auth()->user()->id,
                    'filename' => $filename
                ]
            );

            return null;
        }
    }

    /**
     * Remove Temporarily Stored Xml file.
     */
    public function removeTemporaryXmlFolder()
    {
        $filePath = $this->temporaryXmlStorage();
        $this->filesystem->deleteDirectory($filePath);
    }

    /**
     * Returns errors from the xml
     * @param $filename
     * @param $version
     */
    public function parseXmlErrors($filename, $version)
    {
        $filePath = $this->temporaryXmlStorage($filename);
        $xml      = $this->loadXml($filePath);
        $xmlLines = $this->xmlService->formatUploadedXml($xml);
        $messages = $this->xmlService->getSchemaErrors($xml, $version);
        session(['xmlLines' => $xmlLines, 'messages' => $messages]);
        session()->save();
    }

    /**
     * Load the xml from the given filePath
     * @param $filePath
     * @return string
     */
    protected function loadXml($filePath)
    {
        libxml_use_internal_errors(true);

        $document = new DOMDocument();
        $document->load($filePath);

        return $document->saveXML();
    }

}
