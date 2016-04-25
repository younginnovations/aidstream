<?php namespace App\Core\SuperAdmin;

use App\Http\API\CKAN\CkanClient;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;
use App\Models\Settings;
use Psr\Log\LoggerInterface;

/**
 * Class CorrectionService
 * @package App\Core\SuperAdmin
 */
class CorrectionService
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var null
     */
    protected $publisherMetaData = null;

    /**
     * @var null
     */
    protected $organization = null;

    /**
     * @var ActivityPublished
     */
    protected $activityPublished;

    /**
     * CorrectionService constructor.
     * @param ActivityPublished $activityPublished
     * @param LoggerInterface   $logger
     */
    public function __construct(ActivityPublished $activityPublished, LoggerInterface $logger)
    {
        $this->activityPublished = $activityPublished;
        $this->logger            = $logger;
    }

    /**
     * @param ActivityPublished $file
     * @return bool
     */
    public function isLinkedToRegistry(ActivityPublished $file)
    {
        return ($file->published_to_register == 1);
    }

    /**
     * @param ActivityPublished $file
     * @return bool
     */
    public function delete(ActivityPublished $file)
    {
        $activityFiles = $file->published_activities;
        $xmlFile       = $file->filename;
        $currentUser   = auth()->user();

        try {
            $file->delete();

            if (file_exists($xmlFile)) {
                unlink($this->getFilePath($xmlFile));

                $this->deleteActivityXmlFiles($activityFiles);
            }

            $this->logger->info(sprintf('%s (%s) deleted file %s', $currentUser->name, $currentUser->username, $xmlFile));

            return true;
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('%s (%s) tried deleting a file %s', $currentUser->name, $currentUser->username, $xmlFile));

            return false;
        }
    }

    /**
     * Delete the Activities xml files.
     * @param array $activityFiles
     */
    protected function deleteActivityXmlFiles(array $activityFiles)
    {
        foreach ($activityFiles as $file) {
            if (file_exists($file)) {
                unlink($this->getFilePath($file));
            }
        }
    }

    /**
     * Return the filePath.
     * @param $fileName
     * @return string
     */
    protected function getFilePath($fileName)
    {
        return sprintf('%s%s%s', public_path('files'), config('filesystems.xml'), $fileName);
    }

    /**
     * @param ActivityPublished $file
     * @param Settings          $settings
     * @return bool
     */
    public function unlinkActivityFile(ActivityPublished $file, Settings $settings)
    {
        $publishedFileId = explode('.', $file->filename)[0];
        $apiKey          = $this->extract('api_id', $settings->toArray());
        $registry        = $this->initializeRegistry('http://iatiregistry.org/api/', $apiKey);
        $currentUser     = auth()->user();

        try {
            $response = $registry->package_delete($publishedFileId);

            $this->logger->info(
                sprintf('%s (%s) unlinked the file %s from the IATI registry', $currentUser->name, $currentUser->username, $file->filename),
                [
                    'response' => $response
                ]
            );

            return true;
        } catch (\Exception $exception) {
            $this->logger->error($exception);
        }

        return false;
    }

    /**
     * Gets a CkanClient instance.
     * @param $apiUrl
     * @param $apiKey
     * @return CkanClient
     */
    protected function initializeRegistry($apiUrl, $apiKey)
    {
        return new CkanClient($apiUrl, $apiKey);
    }

    /**
     * Get Publisher data from IATI Registry.
     * @param Organization $organization
     * @return $this
     */
    public function getPublisherDataFor(Organization $organization)
    {
        $settings    = $organization->settings->toArray();
        $apiKey      = $this->extract('api_id', $settings);
        $publisherId = $this->extract('publisher_id', $settings);

        try {
            $registry           = $this->initializeRegistry('http://iatiregistry.org/api/', $apiKey);
            $this->organization = $organization;

            $this->publisherMetaData = json_decode($registry->package_search($publisherId));
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('Package could not be found'));
        }

        return $this;
    }

    /**
     * Extract Api Key from Settings.
     * @param       $key
     * @param array $settings
     * @return string
     */
    protected function extract($key, array $settings)
    {
        if (array_key_exists('registry_info', $settings)) {
            if (array_key_exists(0, $settings['registry_info'])) {
                if (array_key_exists($key, $settings['registry_info'][0])) {
                    return $settings['registry_info'][0][$key];
                }
            }
        }

        return '';
    }

    /**
     * Sync Publisher data from the IATI Registry.
     * @return bool
     */
    public function syncPublisherData()
    {
        if (!is_null($this->publisherMetaData) && !is_null($this->organization)) {
            $currentlyPublishedActivities = $this->getCurrentlyPublishedActivities();

            $actuallyPublishedActivities = $this->publisherMetaData->result->results;

            return $this->syncDatabase($currentlyPublishedActivities, $actuallyPublishedActivities);
        }
    }

    /**
     * Sync Aidstream Database with the data on the IATI Registry.
     * @param $currentlyPublishedActivities
     * @param $actuallyPublishedActivities
     * @return bool
     */
    protected function syncDatabase($currentlyPublishedActivities, $actuallyPublishedActivities)
    {
        $actuallyPublishedFiles = $this->getXmlFileName($actuallyPublishedActivities, true);

        foreach ($currentlyPublishedActivities as $publishedActivity) {
            if (!in_array($publishedActivity->filename, $actuallyPublishedFiles)) {
                $this->unlink($publishedActivity);
            } else {
                $this->unlink($publishedActivity, true);
            }
        }

        return true;
    }

    /**
     * Gets the Xml file names.
     * @param      $dataSet
     * @param bool $multiple
     * @return array|string
     */
    protected function getXmlFileName($dataSet, $multiple = false)
    {
        $fileNames = [];

        if (!$multiple && !is_array($dataSet)) {
            return sprintf('%s.xml', $dataSet->name);
        }

        foreach ($dataSet as $data) {
            $fileNames[] = sprintf('%s.xml', $data->name);
        }

        return $fileNames;
    }

    /**
     * Gets the records for the currently published activities from the Aidstream database.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected function getCurrentlyPublishedActivities()
    {
        return $this->activityPublished->query()->where('organization_id', '=', $this->organization->id)->get();
    }

    /**
     * Remove the linkage of an Activity Xml file from the IATI Registry from the Aidstream Database.
     * @param      $publishedActivity
     * @param bool $resync
     */
    protected function unlink($publishedActivity, $resync = false)
    {

        $publishedActivities                   = $publishedActivity->published_activities;
        $publishedActivity->published_activities = array_unique($publishedActivities);

        if ($resync) {
            $publishedActivity->published_to_register = 1;

            $publishedActivity->save();

            return;
        }

        if ($publishedActivity->published_to_register) {
            $publishedActivity->published_to_register = 0;

            $publishedActivity->save();
        }
    }
}
