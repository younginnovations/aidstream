<?php namespace App\Core\SuperAdmin;

use App\Http\API\CKAN\CkanClient;
use App\Models\ActivityPublished;
use App\Models\Organization\Organization;
use App\Models\OrganizationPublished;
use App\Models\Settings;
use Exception;
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
     * @var OrganizationPublished
     */
    protected $organizationPublished;

    /**
     * CorrectionService constructor.
     * @param ActivityPublished     $activityPublished
     * @param LoggerInterface       $logger
     * @param OrganizationPublished $organizationPublished
     */
    public function __construct(ActivityPublished $activityPublished, LoggerInterface $logger, OrganizationPublished $organizationPublished)
    {
        $this->activityPublished     = $activityPublished;
        $this->logger                = $logger;
        $this->organizationPublished = $organizationPublished;
    }

    /**
     * @param ActivityPublished $file
     * @return bool
     */
    public function isLinkedToRegistry($file)
    {
        return ($file->published_to_register == 1);
    }

    /**
     * @param ActivityPublished $file
     * @return bool
     */
    public function delete($file)
    {
        $xmlFile     = $file->filename;
        $currentUser = auth()->user();

        try {
            $filePath = $this->getFilePath($xmlFile);

            $file->delete();
            if (file_exists($filePath)) {
                unlink($filePath);
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
            $filePath = $this->getFilePath($file);

            if (file_exists($filePath)) {
                unlink($filePath);
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
     * Unlink file from the IATI registry.
     * @param          $file
     * @param Settings $settings
     * @return bool
     */
    public function unlinkFile($file, Settings $settings)
    {
        $publishedFileId = explode('.', $file->filename)[0];

        return $this->unlinkFromRegistry($publishedFileId, $file, $settings);
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
//            $registry           = $this->initializeRegistry(env('REGISTRY_URL'), $apiKey);
            $this->organization = $organization;

            $this->publisherMetaData = json_decode($this->searchForPublisher($publisherId));
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

        return false;
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
        $publishedActivities                     = $publishedActivity->published_activities;
        $publishedActivity->published_activities = $publishedActivities ? array_unique($publishedActivities) : null;

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

    /**
     * @param $publishedFileId
     * @param $file
     * @param $settings
     * @return bool
     */
    protected function unlinkFromRegistry($publishedFileId, $file, $settings)
    {
        try {
            $apiKey   = $this->extract('api_id', $settings->toArray());
            $registry = $this->initializeRegistry(env('REGISTRY_URL'), $apiKey);

            $response    = $registry->package_delete($publishedFileId);
            $currentUser = auth()->user();

            $this->logger->info(
                sprintf('%s (%s) unlinked the file %s from the IATI registry', $currentUser->name, $currentUser->username, $file->filename),
                [
                    'response' => $response
                ]
            );

            return true;
        } catch (Exception $exception) {
            $this->logger->error(
                $exception->getMessage(),
                [
                    'trace' => $exception->getTraceAsString(),
                ]
            );

            return false;
        }
    }

    /**
     * Sync Organization data with the IATI Registry.
     * @return bool
     */
    public function syncOrgData()
    {
        if (!is_null($this->publisherMetaData) && !is_null($this->organization)) {
            $organizationFile = $this->getCurrentlyPublishedOrganizationFile();

            if ($actuallyPublishedOrganizationFile = $this->getOrganizationFileName()) {
                return $this->syncOrgDatabase($organizationFile, $actuallyPublishedOrganizationFile);
            }

            return false;
        }

        return false;
    }

    /**
     * Get the OrganizationPublished model.
     * @return mixed
     */
    protected function getCurrentlyPublishedOrganizationFile()
    {
        return $this->organizationPublished->where('organization_id', '=', $this->organization->id)->get();
    }

    /**
     * Get the full Organization filename.
     * @return null
     */
    protected function getOrganizationFileName()
    {
        $publisherId = $this->organization->settings->registry_info[0]['publisher_id'] . '-org';

        foreach ($this->publisherMetaData->result->results as $result) {
            if ($result->name == $publisherId) {
                return $result->name;
            }
        }

        return null;
    }

    /**
     * Sync OrganizationPublished table with the IATI Registry.
     * @param $organizationFiles
     * @param $actuallyPublishedOrganizationFile
     * @return bool
     */
    protected function syncOrgDatabase($organizationFiles, $actuallyPublishedOrganizationFile)
    {
        $actuallyPublishedOrganizationFile = sprintf('%s.xml', $actuallyPublishedOrganizationFile);

        foreach ($organizationFiles as $publishedFile) {
            if ($publishedFile->filename == $actuallyPublishedOrganizationFile) {
                $publishedFile->published_to_register = 1;
            } else {
                $publishedFile->published_to_register = 0;
            }

            $publishedFile->save();
        }

        return true;
    }

    /**
     * Search for a publisher with a specific publisherId.
     * @param $publisherId
     * @return string
     */
    protected function searchForPublisher($publisherId)
    {
        $apiHost = env('REGISTRY_URL');
        $uri     = 'action/package_search';
        $url     = sprintf('%s%s?q=&fq=organization:%s', $apiHost, $uri, $publisherId);

        return file_get_contents($url);
    }
}
