<?php namespace App\Services\Settings;


use App\Exceptions\Aidstream\Workflow\ApiKeyIncorrectException;
use App\Exceptions\Aidstream\Workflow\PublisherNotFoundException;
use App\Exceptions\Aidstream\XmlFileNotFound;
use App\Models\Organization\Organization;
use App\Services\Organization\OrganizationManager;
use App\Services\Publisher\Publisher;
use App\Services\Publisher\Traits\RegistryApiInvoker;
use App\Services\SettingsManager;
use App\Services\Workflow\Traits\ExceptionParser;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\DatabaseManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class ChangeHandler
 * @package App\Services\Settings
 */
class ChangeHandler
{
    use RegistryApiInvoker, ExceptionParser;

    /**
     * Storage path of xml files.
     */
    const XML_BASE_FILE_PATH = 'files/xml';

    /**
     * Not authorized error code.
     */
    const NOT_AUTHORIZED_ERROR_CODE = 403;

    /**
     * Package not found error code.
     */
    const PACKAGE_NOT_FOUND_CODE = 404;

    /**
     * @var OrganizationManager
     */
    private $organizationService;
    /**
     * @var Publisher
     */
    protected $publisher;

    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var bool
     */
    protected $apiKeyCorrectness = false;
    /**
     * @var SettingsManager
     */
    protected $settingsManager;
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @var
     */
    protected $changedFiles = [];

    /**
     * @var array
     */
    protected $publishedFiles = [];
    /**
     * @var array
     */
    protected $unlinkedFiles = [];

    /**
     * ChangeHandler constructor.
     * @param OrganizationManager $organizationService
     * @param SettingsManager     $settingsManager
     * @param Publisher           $publisher
     * @param Filesystem          $filesystem
     * @param LoggerInterface     $logger
     * @param DatabaseManager     $databaseManager
     */
    public function __construct(
        OrganizationManager $organizationService,
        SettingsManager $settingsManager,
        Publisher $publisher,
        Filesystem $filesystem,
        LoggerInterface $logger,
        DatabaseManager $databaseManager
    ) {
        $this->organizationService = $organizationService;
        $this->publisher           = $publisher;
        $this->logger              = $logger;
        $this->filesystem          = $filesystem;
        $this->settingsManager     = $settingsManager;
        $this->databaseManager     = $databaseManager;
    }

    /**
     * Handle when the publisher id is changed.
     * If new publisher id is not valid then publisher not found exception is thrown.
     *
     * @param $organizationId
     * @param $publisherId
     * @param $newApiKey
     * @param $settings
     * @return array
     */
    public function handle($organizationId, $publisherId, $newApiKey, $settings)
    {
        try {
            $publisherIdResponse = $this->searchForPublisher($publisherId);

            if (!$this->checkPublisherValidity($publisherIdResponse, $publisherId)) {
                throw new PublisherNotFoundException('Publisher Id is incorrect');
            }

            $this->setApiKeyCorrectness($newApiKey, $publisherIdResponse);

            $organization              = $this->getOrganization($organizationId);
            $publishedOrganizationData = $this->getPublishedOrganizationData($organization)->first();
            $publishedActivities       = $this->getPublishedActivities($organization);

            $this->databaseManager->beginTransaction();

            $this->cloneAndPublishDatasets($publishedActivities, $publishedOrganizationData, $settings, $publisherId, $organization, $newApiKey);
            $this->unlinkAndSaveDatasets($publishedActivities, $publishedOrganizationData, $settings, $publisherId, $organization, $newApiKey);
            $this->savePublisherId($settings, $publisherId, $newApiKey);

            $this->databaseManager->commit();

            return ['status' => true];
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            $this->logger->error($exception->getMessage(), ['Organization Id' => $organizationId]);

            return $this->parse($exception);
        }
    }

    /**
     * Set the correctness of the api key entered by user.
     *
     * @param $newApiKey
     * @param $publisherIdResponse
     */
    protected function setApiKeyCorrectness($newApiKey, $publisherIdResponse)
    {
        $apiKeyResponse = $this->searchForApiKey($newApiKey);

        if ($apiKeyResponse) {
            if ($this->isApiKeyOfThePublisher(json_decode($publisherIdResponse, true), $apiKeyResponse)) {
                $this->apiKeyCorrectness = true;
            }
        }

    }

    /**
     * Check if the organization has published any organization data.
     *
     * @param $publishedOrganizationData
     * @return bool
     */
    public function hasPublishedAnyOrganizationFile($publishedOrganizationData)
    {
        if (count($publishedOrganizationData) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Check if the organization has published any activity.
     *
     * @param $publishedActivities
     * @return bool
     */
    public function hasPublishedAnyActivityFile($publishedActivities)
    {
        if (count($publishedActivities) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Rename the filename for the activities that are published to registry.
     * Update the record in database.
     *
     * @param $publisherId
     * @param $activities
     * @return array
     */
    protected function returnFilenameForIncludedActivities($publisherId, $activities)
    {
        $newFilename = [];

        foreach ($activities as $activityId => $activity) {
            $newFilename[$activityId] = $this->generateNewFilename($publisherId, $activity);
            $this->changeFilenameOfStoredXml($newFilename[$activityId], $activity);
        }

        return $newFilename;
    }

    /**
     * Search for api key in IATI Registry.
     *
     * @param $apiKey
     * @return mixed
     * @throws ApiKeyIncorrectException
     */
    public function searchForApiKey($apiKey)
    {
        try {
            return json_decode($this->request('dashboard_activity_list', null, $apiKey), true);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Check if the api key is of the entered publisher id.
     *
     * @param $publisherIdResponse
     * @param $apiKeyResponse
     * @return bool
     */
    public function isApiKeyOfThePublisher($publisherIdResponse, $apiKeyResponse)
    {
        $publisherIdResponseUserId = getVal($apiKeyResponse, ['result', 0, 'data', 'package', 'owner_org']);

        return ($publisherIdResponseUserId == getVal($publisherIdResponse, ['result', 'id'])) ? true : false;
    }

    /**
     * Returns new filename according to new publisher id.
     *
     * @param $newPublisherId
     * @param $oldFilename
     * @return string
     */
    protected function generateNewFilename($newPublisherId, $oldFilename)
    {
        $filename = explode('-', $this->extractPackage($oldFilename));

        return sprintf('%s-%s.xml', $newPublisherId, end($filename));
    }

    /**
     * Returns published activities of the organization.
     *
     * @param Organization $organization
     * @return mixed
     */
    public function getPublishedActivities(Organization $organization)
    {
        return $organization->publishedFiles;
    }

    /**
     * Returns published organization data.
     *
     * @param Organization $organization
     * @return mixed
     */
    public function getPublishedOrganizationData(Organization $organization)
    {
        return $organization->organizationPublished;
    }

    /**
     * Returns organization model.
     *
     * @param $organizationId
     * @return Organization
     */
    public function getOrganization($organizationId)
    {
        return $this->organizationService->getOrganization($organizationId);
    }

    /**
     * Returns settings of the organization.
     *
     * @param Organization $organization
     * @return mixed
     */
    protected function getSettings(Organization $organization)
    {
        return $organization->settings;
    }

    /**
     * Change the
     * @param $newFilename
     * @param $oldFilename
     * @return bool
     * @throws Exception
     */
    protected function changeFilenameOfStoredXml($newFilename, $oldFilename)
    {
        try {
            copy($this->getXmlPath($oldFilename), $this->getXmlPath($newFilename));
            $this->changedFiles[] = ['old' => $oldFilename, 'new' => $newFilename];

            return true;
        } catch (Exception $exception) {
            throw new XmlFileNotFound(trans('error.file_not_found', ['file' => $oldFilename]));
        }
    }

    /**
     * @param null $filename
     * @return string
     */
    protected function getXmlPath($filename = null)
    {
        if ($filename) {
            return sprintf('%s/%s/%s', public_path(), self::XML_BASE_FILE_PATH, $filename);
        }

        return sprintf('%s/%s', public_path(), self::XML_BASE_FILE_PATH);
    }

    /**
     * Changes for the activity data.
     *
     * @param $publishedActivities
     * @param $publisherId
     * @param $newApiKey
     * @return array
     */
    public function changesForActivityData($publishedActivities, $publisherId, $newApiKey)
    {
        $changes = [];
        $linkage = false;

        foreach ($publishedActivities as $index => $publishedActivity) {
            $oldFilename                    = $publishedActivity->filename;
            $newFilename                    = $this->generateNewFilename($publisherId, $oldFilename);
            $changes[$index]['oldFilename'] = $oldFilename;
            $changes[$index]['newFilename'] = $newFilename;
            (!$this->isPackageAvailable($this->extractPackage($oldFilename), $newApiKey)) ?: $linkage = true;
        }
        $changes['linkage'] = $linkage;

        return $changes;
    }

    /**
     * Changes for organization data.
     *
     * @param $publishedOrganizationData
     * @param $publisherId
     * @param $newApiKey
     * @return array
     */
    public function changesForOrganizationData($publishedOrganizationData, $publisherId, $newApiKey)
    {
        $changes                   = [];
        $oldFilename               = $publishedOrganizationData->filename;
        $newFilename               = $this->generateNewFilename($publisherId, $oldFilename);
        $changes[0]['oldFilename'] = $oldFilename;
        $changes[0]['newFilename'] = $newFilename;
        $changes[0]['linkage']     = ($this->isPackageAvailable($this->extractPackage($oldFilename), $newApiKey)) ? true : false;

        return $changes;
    }

    /**
     * Save the publisher id changes to database.
     *
     * @param $settings
     * @param $newPublisherId
     * @throws Exception
     */
    protected function savePublisherId($settings, $newPublisherId, $newApiKey)
    {
        try {
            $registryInfo                           = $settings->registry_info;
            $registryInfo[0]['publisher_id']        = $newPublisherId;
            $registryInfo[0]['api_id']              = $newApiKey;
            $registryInfo[0]['publisher_id_status'] = 'Correct';

            $settings->registry_info = $registryInfo;
            $settings->save();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Clone the xml files present in the database.
     * Publish the new package in the IATI Registry.
     *
     * @param $publishedActivities
     * @param $publishedOrganizationData
     * @param $settings
     * @param $publisherId
     * @param $organization
     * @param $newApiKey
     * @throws Exception
     */
    protected function cloneAndPublishDatasets($publishedActivities, $publishedOrganizationData, $settings, $publisherId, $organization, $newApiKey)
    {
        try {
            if ($this->hasPublishedAnyOrganizationFile($publishedOrganizationData)) {
                $this->cloneAndPublishOrganizationData($publishedOrganizationData, $settings, $publisherId, $organization, $newApiKey);
            }

            if ($this->hasPublishedAnyActivityFile($publishedActivities)) {
                $this->cloneAndPublishActivities($publishedActivities, $settings, $publisherId, $organization, $newApiKey);
            }
        } catch (Exception $exception) {
            $this->rollBackPublishedFiles($newApiKey);
            throw  $exception;
        }
    }

    /**
     * Unlink the organization and activity dataset from the IATI Registry.
     * Save the changes in database.
     *
     * @param $publishedActivities
     * @param $publishedOrganizationData
     * @param $settings
     * @param $publisherId
     * @param $organization
     * @param $newApiKey
     * @throws Exception
     */
    protected function unlinkAndSaveDatasets($publishedActivities, $publishedOrganizationData, $settings, $publisherId, $organization, $newApiKey)
    {
        try {
            if ($this->hasPublishedAnyOrganizationFile($publishedOrganizationData)) {
                $this->unlinkAndSaveOrganizationData($publishedOrganizationData, $settings, $publisherId);
            }

            if ($this->hasPublishedAnyActivityFile($publishedActivities)) {
                $this->unlinkAndSaveActivities($publishedActivities, $settings, $publisherId);
            }

        } catch (Exception $exception) {
            $this->rollBackUnlinkedFiles($newApiKey, $settings, $organization, $publishedActivities, $publishedOrganizationData);
            throw  $exception;
        }
    }

    /**
     * Clone the old organization xml file.
     * Publish the organization xml file to the IATI Registry.
     *
     * @param $publishedOrganizationData
     * @param $settings
     * @param $publisherId
     * @param $organization
     * @param $newApiKey
     * @return bool
     * @throws Exception
     */
    protected function cloneAndPublishOrganizationData($publishedOrganizationData, $settings, $publisherId, $organization, $newApiKey)
    {
        try {
            $settings    = $settings->toArray();
            $oldFilename = $publishedOrganizationData->filename;
            $newFilename = $this->generateNewFilename($publisherId, $oldFilename);
            $oldApiKey   = getVal($settings, ['registry_info', 0, 'api_id']);

            if ($oldFilename == $newFilename) {
                return true;
            }

            $this->changeFilenameOfStoredXml($newFilename, $oldFilename);
            $packageAvailability = $this->isPackageAvailable($this->extractPackage($oldFilename), $oldApiKey);

            if ($packageAvailability && $this->apiKeyCorrectness) {
                $settings['registry_info'][0]['publisher_id'] = $publisherId;
                $settings['registry_info'][0]['api_id']       = $newApiKey;

                $publishedOrganizationData->filename = $newFilename;
                $this->publisher->publishFile(
                    getVal($settings, ['registry_info'], []),
                    $publishedOrganizationData,
                    $organization,
                    getVal($settings, ['publishing_type'])
                );
                $publishedOrganizationData->filename = $oldFilename;

                $this->publishedFiles[] = ['newPackage' => $this->extractPackage($newFilename)];
            }

            return true;
        } catch (Exception $exception) {
            throw $exception;
        }

    }

    /**
     * Clone the old xml file.
     * Publish the old package to the IATI Registry.
     *
     * @param $publishedActivities
     * @param $settings
     * @param $publisherId
     * @param $organization
     * @param $newApiKey
     * @return bool
     * @throws Exception
     */
    protected function cloneAndPublishActivities($publishedActivities, $settings, $publisherId, $organization, $newApiKey)
    {
        try {
            $settings  = $settings->toArray();
            $oldApiKey = getVal($settings, ['registry_info', 0, 'api_id']);

            foreach ($publishedActivities as $index => $publishedActivity) {
                $oldFilename = $publishedActivity->filename;
                $newFilename = $this->generateNewFilename($publisherId, $oldFilename);

                if ($oldFilename == $newFilename) {
                    break;
                }

                $this->changeFilenameOfStoredXml($newFilename, $oldFilename);

                $packageAvailability = $this->isPackageAvailable($this->extractPackage($oldFilename), $oldApiKey);

                if ($packageAvailability && $this->apiKeyCorrectness) {
                    $settings['registry_info'][0]['publisher_id'] = $publisherId;
                    $settings['registry_info'][0]['api_id']       = $newApiKey;
                    $publishedActivity->filename                  = $newFilename;

                    $this->publisher->publishFile(
                        getVal($settings, ['registry_info'], []),
                        $publishedActivity,
                        $organization,
                        getVal($settings, ['publishing_type'])
                    );
                    $publishedActivity->filename = $oldFilename;

                    $this->publishedFiles[] = ['newPackage' => $this->extractPackage($newFilename)];
                }
            }

            return true;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Unlink the old package.
     * Save in database.
     *
     * @param $publishedActivities
     * @param $settings
     * @param $publisherId
     * @return bool
     * @throws Exception
     */
    protected function unlinkAndSaveActivities($publishedActivities, $settings, $publisherId)
    {
        try {
            $settings  = $settings->toArray();
            $oldApiKey = getVal($settings, ['registry_info', 0, 'api_id']);

            foreach ($publishedActivities as $index => $publishedActivity) {
                $oldFilename = $publishedActivity->filename;
                $newFilename = $this->generateNewFilename($publisherId, $oldFilename);

                if ($oldFilename == $newFilename) {
                    break;
                }

                $publishedActivity->filename             = $newFilename;
                $publishedActivity->published_activities = $this->returnFilenameForIncludedActivities($publisherId, $publishedActivity->published_activities);
                $publishedActivity->save();

                $packageAvailability = $this->isPackageAvailable($this->extractPackage($oldFilename), $oldApiKey);

                if ($packageAvailability && $this->apiKeyCorrectness) {
                    $this->deletePackage($oldApiKey, $this->extractPackage($oldFilename));

                    $this->unlinkedFiles[] = $oldFilename;
                }
            }

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            throw $exception;
        }
    }

    /**
     * Unlink the old package.
     * Save the record in database.
     *
     * @param $publishedOrganizationData
     * @param $settings
     * @param $publisherId
     * @return bool
     * @throws Exception
     */
    protected function unlinkAndSaveOrganizationData($publishedOrganizationData, $settings, $publisherId)
    {
        try {
            $settings    = $settings->toArray();
            $oldFilename = $publishedOrganizationData->filename;
            $newFilename = $this->generateNewFilename($publisherId, $oldFilename);
            $oldApiKey   = getVal($settings, ['registry_info', 0, 'api_id']);

            if ($oldFilename == $newFilename) {
                return true;
            }

            $packageAvailability = $this->isPackageAvailable($this->extractPackage($oldFilename), $oldApiKey);

            $publishedOrganizationData->filename = $newFilename;
            $publishedOrganizationData->save();

            if ($packageAvailability && $this->apiKeyCorrectness) {
                $this->deletePackage($oldApiKey, $this->extractPackage($oldFilename));

                $this->unlinkedFiles = ['oldPackage' => $this->extractPackage($oldFilename)];
            }

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();
            throw $exception;
        }
    }

    /**
     * Unlink recently published packages.
     *
     * @param $newApiKey
     */
    protected function rollBackPublishedFiles($newApiKey)
    {
        foreach ($this->publishedFiles as $index => $file) {
            $this->deletePackage($newApiKey, getVal($file, ['newPackage']));
        }
    }

    /**
     * Unlink newly published package
     * Publish unlinked old package.
     *
     * @param $newApiKey
     * @param $settings
     * @param $organization
     * @param $publishedActivities
     * @param $publishedOrganizationData
     */
    protected function rollBackUnlinkedFiles($newApiKey, $settings, $organization, $publishedActivities, $publishedOrganizationData)
    {
        $this->rollBackPublishedFiles($newApiKey);

        foreach ($publishedActivities as $index => $publishedActivity) {
            $oldFilename = $publishedActivity->filename;

            if (in_array($oldFilename, $this->unlinkedFiles)) {
                $this->publisher->publishFile(
                    getVal($settings, ['registry_info'], []),
                    $publishedActivity,
                    $organization,
                    getVal($settings, ['publishing_type'])
                );
            }
        }

        $oldOrgFilename = $publishedOrganizationData->filename;

        if (in_array($oldOrgFilename, $this->unlinkedFiles)) {
            $this->publisher->publishFile(
                getVal($settings, ['registry_info'], []),
                $publishedOrganizationData,
                $organization,
                getVal($settings, ['publishing_type'])
            );

        }
    }
}

