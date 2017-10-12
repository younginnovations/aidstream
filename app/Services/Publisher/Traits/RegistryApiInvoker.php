<?php namespace App\Services\Publisher\Traits;


use App\Http\API\CKAN\CkanClient;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class RegistryApiInvoker
 * @package App\Services\Publisher\Traits
 */
trait RegistryApiInvoker
{
    /**
     * Initialize the GuzzleHttp\Client instance
     * @return mixed
     */
    protected function initGuzzleClient()
    {
        return app()->make(Client::class);
    }

    /**
     * Make an api request to the given action.
     *
     * @param        $action
     * @param        $requestParameter
     * @param null   $apiKey
     * @return mixed
     */
    protected function request($action, $requestParameter = null, $apiKey = null)
    {
        $apiHost = env('REGISTRY_URL');
        $url     = ($requestParameter) ? sprintf("%saction/%s?id=%s", $apiHost, $action, $requestParameter) :
            sprintf("%saction/%s", $apiHost, $action);
        $client  = $this->initGuzzleClient();

        return $client->get($url, ['headers' => ['authorization' => $apiKey]])->getBody()->getContents();
    }

    /**
     * Search for a publisher with a specific publisherId.
     *
     * @param $publisherId
     * @return string
     * @throws Exception
     */
    public function searchForPublisher($publisherId)
    {
        try {
            return $this->request('organization_show', $publisherId);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Check if the publisher returned by the IATI Registry Api is valid with given publisher.
     *
     * @param $publisherData
     * @param $publisherId
     * @return bool
     */
    public function checkPublisherValidity($publisherData, $publisherId)
    {
        $publisherData = json_decode($publisherData);

        return $publisherData ? ($publisherData->result->name == $publisherId) : false;
    }

    /**
     * Delete/Unlink the provided package from IATI Registry.
     *
     * @param $apiKey
     * @param $packageId
     * @throws Exception
     */
    protected function deletePackage($apiKey, $packageId)
    {
        try {
            $api = new CkanClient(env('REGISTRY_URL'), $apiKey);
            $api->package_delete($packageId);
        } catch (Exception $exception) {
            throw $exception;
        }

    }

    /**
     * Extract the package name from the published filename.
     *
     * @param $filename
     * @return string
     */
    protected function extractPackage($filename)
    {
        return array_first(
            explode('.', $filename),
            function () {
                return true;
            }
        );
    }

    /**
     * Check if the package is already present in IATI Registry.
     * Returns true if package is present/deleted.
     *
     * @param $packageId
     * @param $apiKey
     * @return bool
     * @throws Exception
     */
    protected function isPackageAvailable($packageId, $apiKey)
    {
        try {
            $response = json_decode($this->request('package_show', $packageId, $apiKey), true);

            if (getVal($response, ['result', 'state']) == 'deleted') {
                return true;
            }

            return (getVal($response, ['success']) === true) ? true : false;

        } catch (Exception $exception) {
            if ($exception instanceof ClientException) {
                if ($exception->getResponse()->getStatusCode() == 404) {
                    return false;
                }

                throw  $exception;
            }

            throw  $exception;
        }
    }
}

