<?php namespace App\Services\Workflow\Registry;

use App\Http\API\CKAN\CkanClient;

/**
 * Class RegistryApiHandler
 * @package App\Exceptions\Aidstream\Workflow\Registry
 */
abstract class RegistryApiHandler
{
    /**
     * @var
     */
    protected $client;

    /**
     * @var
     */
    protected $publisherId;

    /**
     * @var
     */
    protected $apiKey;

    /**
     * Initialize an CkanClient instance.
     * @param $url
     * @param $key
     * @return RegistryApiHandler
     */
    public function init($url, $key)
    {
        $this->client = new CkanClient($url, $key);
        $this->apiKey = $key;

        return $this;
    }

    /**
     * @param $publisherId
     */
    public function setPublisher($publisherId)
    {
        $this->publisherId = $publisherId;
    }
}
