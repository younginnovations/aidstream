<?php namespace App\Np\Services\Traits;


use GuzzleHttp\Client;

/**
 * Class GeocodeReverser
 * @package App\Np\Services\Traits
 */
trait GeocodeReverser
{

    /**
     * @param $latitude
     * @param $longitude
     * @return array|string
     */
    protected function reverse($latitude, $longitude)
    {
        $response = json_decode($this->response($latitude, $longitude), true);

        return getVal($response, ['display_name']);
    }

    /**
     * @param $latitude
     * @param $longitude
     * @return mixed
     */
    protected function response($latitude, $longitude)
    {
        $client  = $this->client();
        $url     = sprintf('http://nominatim.openstreetmap.org/reverse.php?format=json&lat=%s&lon=%s&accept-language=en', $latitude, $longitude);
        $request = $client->get($url);

        return $request->getBody()->getContents();
    }

    /**
     * @return mixed
     */
    protected function client()
    {
        return app()->make(Client::class);
    }
}

