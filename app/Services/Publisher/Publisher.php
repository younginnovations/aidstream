<?php namespace App\Services\Publisher;

use App\Services\Publisher\Traits\RegistryApiInvoker;
use Exception;
use App\Http\API\CKAN\CkanClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Workflow\Registry\RegistryApiHandler;
use App\Exceptions\Aidstream\Workflow\PublisherNotFoundException;


/**
 * Class Publisher
 * @package App\Services\Publisher
 */
class Publisher extends RegistryApiHandler
{
    use RegistryApiInvoker;

    const NOT_AUTHORIZED_ERROR_CODE = 403;
    /**
     *
     */
    const PACKAGE_FORBIDDEN_CODE = 403;

    /**
     *
     */
    const PACKAGE_NOT_FOUND_CODE = 404;

    /**
     * @var null
     */
    protected $file = null;

    /**
     * @param       $registryInfo
     * @param       $organization
     * @param       $publishingType
     * @param array $changes
     * @throws PublisherNotFoundException
     * @throws Exception
     */
    public function publish($registryInfo, $organization, $publishingType, array $changes = [])
    {
        try {
            $this->init(env('REGISTRY_URL'), getVal($registryInfo, [0, 'api_id'], ''))
                 ->setPublisher(getVal($registryInfo, [0, 'publisher_id'], ''));

            /* Depcricated */
//        $this->client->package_search($this->publisherId)
            try {
                $this->searchForPublisher($this->publisherId);
            } catch (Exception $exception) {
                throw new PublisherNotFoundException('Publisher not found.');
            }

//            if (!$this->checkPublisherValidity($publisherData, $this->publisherId)) {
//                throw new PublisherNotFoundException('Publisher not found.');
//            }

            if ($changes) {
                $this->publishSegmentationChanges($changes, $organization, $publishingType);
            } else {
                if ($this->file) {
                    $this->publishIntoRegistry($organization, $publishingType);
                }
            }
        } catch (ClientException $exception) {
            throw $exception;
        }
    }

    /**
     * Returns the request header payload while publishing any files to the IATI Registry.
     * @param      $organization
     * @param      $filename
     * @param      $publishingType
     * @param null $publishedFile
     * @return array
     * @internal param $data
     */
    protected function generatePayload($organization, $filename, $publishingType, $publishedFile = null)
    {
        $code     = $this->getCode($filename);
        $key      = $this->getKey($code);
        $fileType = $this->getFileType($code);
        $title    = $this->extractTitle($organization, $publishingType, $code, $fileType);

        if (!$publishedFile) {
            return $this->formatHeaders($this->extractPackage($filename), $organization, $this->file, $key, $fileType, $title);
        }

        return $this->formatHeaders($this->extractPackage($filename), $organization, $this->file, $key, $fileType, $title);
    }

    /**
     * Get the required key for the code provided.
     * @param $code
     * @return string
     */
    protected function getKey($code)
    {
        if ($code == "998") {
            return "Others";
        } elseif (is_numeric($code)) {
            return "region";
        }

        return "country";
    }

    /**
     * Format headers required to publish into the IATI Registry.
     * @param $filename
     * @param $organization
     * @param $publishedFile
     * @param $key
     * @param $code
     * @param $title
     * @return string
     */
    protected function formatHeaders($filename, $organization, $publishedFile, $key, $code, $title)
    {
        $data = [
            'title'        => $title,
            'name'         => $filename,
            'author_email' => $organization->getAdminUser()->email,
            'owner_org'    => $this->publisherId,
            'license_id'   => 'other-open',
            'resources'    => [
                [
                    'format'   => config('xmlFiles.format'),
                    'mimetype' => config('xmlFiles.mimeType'),
                    'url'      => url(sprintf('files/xml/%s.xml', $filename))
                ]
            ],
            "filetype"     => ($code != 'organisation') ? 'activity' : $code,
            $key           => ($code == 'activities' || $code == 'organisation') ? '' : $code,
            "data_updated" => $publishedFile->updated_at->toDateTimeString(),
            "language"     => config('app.locale'),
            "verified"     => "no",
            "state"        => "active"
        ];

        if ($code != 'organisation') {
            $data['activity_count'] = count($publishedFile->published_activities);
        }

        return json_encode($data);
    }

    /**
     * @param        $registryInfo
     * @param        $file
     * @param        $organization
     * @param        $publishingType
     * @throws Exception
     */
    public function publishFile($registryInfo, $file, $organization, $publishingType)
    {
        try {
            $this->setFile($file);
            $this->publish($registryInfo, $organization, $publishingType);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Get the data type or country code/region code from the filename.
     * @param $filename
     * @return array
     */
    protected function getCode($filename)
    {
        $filename = str_replace('.xml', '', $filename);

        return substr($filename, strlen($this->publisherId) + 1);
    }

    /**
     * Extract title for the file being published.
     * @param $organization
     * @param $publishingType
     * @param $code
     * @param $fileType
     * @return string
     */
    protected function extractTitle($organization, $publishingType, $code, $fileType)
    {
        if ($fileType == 'organisation') {
            return $organization->name . ' Organisation File';
        }

        return ($publishingType == "segmented")
            ? $organization->name . ' Activity File-' . strtoupper($code)
            : $organization->name . ' Activity File';
    }

    /**
     * Set the file attribute.
     * @param $file
     */
    protected function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Publish File to the IATI Registry.
     * @param      $organization
     * @param      $filename
     * @param      $publishingType
     * @throws Exception
     */
    protected function publishToRegistry($organization, $filename, $publishingType)
    {
        $data      = $this->generatePayload($organization, $filename, $publishingType);
        $packageId = $this->extractPackage($filename);

        if ($this->isPackageAvailable($packageId, $this->apiKey)) {
            $this->client->package_update($data);
        } else {
            $this->client->package_create($data);
        }

        $this->updateStatus();
    }

    /**
     * Publish Segmentation changes into the IATI Registry.
     * @param $changeDetails
     * @param $organization
     * @param $publishingType
     * @throws Exception
     */
    protected function publishSegmentationChanges($changeDetails, $organization, $publishingType)
    {
        $changes = $changeDetails['changes'];

        foreach ($changes as $filename => $changeDetail) {
            $this->file = $this->getPublishedActivities($organization, $filename);
            $this->publishToRegistry($organization, $filename, $publishingType);
        }
    }

    /**
     * Publish file(s) into the IATI Registry.
     * @param $organization
     * @param $publishingType
     * @throws Exception
     */
    protected function publishIntoRegistry($organization, $publishingType)
    {
        if ($this->file instanceof Collection) {
            foreach ($this->file as $file) {
                $this->publishToRegistry($organization, $file->filename, $publishingType);
            }
        } else {
            $this->publishToRegistry($organization, $this->file->filename, $publishingType);
        }
    }

    /**
     * @return Exception
     */
    protected function updateStatus()
    {
        try {
            $this->file->published_to_register = 1;
            $this->file->save();
        } catch (Exception $exception) {
            return $exception;
        }
    }

    /**
     * @param $code
     * @return mixed|string
     */
    protected function getFileType($code)
    {
        if ($code === 'org' || $code === 'organisation') {
            return 'organisation';
        }

        return $code;
    }

    /**
     * @param $organization
     * @param $filename
     * @return mixed
     */
    protected function getPublishedActivities($organization, $filename)
    {
        return $organization->publishedFiles()->where('filename', '=', $filename)->first();
    }

    /**
     * Unlink a file from the IATI Registry.
     * @param $apiKey
     * @param $changeDetails
     * @return bool
     * @throws Exception
     * @throws \CKAN\NotFoundHttpException
     */
    public function unlink($apiKey, $changeDetails)
    {
        try {
            $api = new CkanClient(env('REGISTRY_URL'), $apiKey);

            foreach ($changeDetails['previous'] as $filename => $previous) {
                $pieces = explode(".", $filename);
                $fileId = array_first(
                    $pieces,
                    function () {
                        return true;
                    }
                );

                if (getVal($previous, ['published_status'])) {
                    $api->package_delete($fileId);
                }
            }

            return true;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}

