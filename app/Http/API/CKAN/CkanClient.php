<?php namespace App\Http\API\CKAN;

use DateTime;
use DateTimeZone;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Alex Perfilov
 * @date   2/24/14
 * Inspired by https://github.com/jeffreybarke/Ckan_client-PHP
 * @link   http://docs.ckan.org/en/latest/api/
 */
class CkanClient
{

    /**
     * @var string
     */
    private $api_url = '';

    /**
     * @var null|string
     */
    private $api_key = null;

    /**
     * cURL handler
     * @var resource
     */
    private $curl_handler;


    /**
     * cURL headers
     * @var array
     */
    private $curl_headers;

    /**
     * HTTP status codes.
     * @var        array
     */
    private $http_status_codes = [
        '200' => 'OK',
        '201' => 'Created',
        '301' => 'Moved Permanently',
        '400' => 'Bad Request',
        '403' => 'Not Authorized',
        '404' => 'Not Found',
        '409' => 'Conflict (e.g. name already exists)',
        '411' => 'Length required',
        '500' => 'Service Error',
        '503' => 'Service unavailable (e.g. CKAN build in progress, or you are banned)'
    ];

    private $resources = [
        'package_register' => 'rest/package',
        'package_entity'   => 'rest/package'
    ];

    /**
     * @param      $api_url
     * @param null $api_key
     */
    public function __construct($api_url, $api_key = null)
    {
        $this->api_url = $api_url;
        $this->api_key = $api_key;
        // Create cURL object.
        $this->curl_handler = curl_init();
        // Follow any Location: headers that the server sends.
        curl_setopt($this->curl_handler, CURLOPT_FOLLOWLOCATION, true);
        // However, don't follow more than five Location: headers.
        curl_setopt($this->curl_handler, CURLOPT_MAXREDIRS, 5);
        // Automatically set the Referrer: field in requests
        // following a Location: redirect.
        curl_setopt($this->curl_handler, CURLOPT_AUTOREFERER, true);
        // Return the transfer as a string instead of dumping to screen.
        curl_setopt($this->curl_handler, CURLOPT_RETURNTRANSFER, true);
        // If it takes more than 5 minutes => fail
        curl_setopt($this->curl_handler, CURLOPT_TIMEOUT, 60 * 5);
        // We don't want the header (use curl_getinfo())
        curl_setopt($this->curl_handler, CURLOPT_HEADER, false);
        // Track the handle's request string
        curl_setopt($this->curl_handler, CURLINFO_HEADER_OUT, true);
        // Attempt to retrieve the modification date of the remote document.
        curl_setopt($this->curl_handler, CURLOPT_FILETIME, true);
        curl_setopt($this->curl_handler, CURLOPT_SSL_VERIFYPEER, false);
        // Initialize cURL headers
        $this->set_headers();
    }

    /**
     * Sets the custom cURL headers.
     * @access    private
     * @return    void
     * @since     Version 0.1.0
     */
    private function set_headers()
    {
        $date               = new DateTime(null, new DateTimeZone('UTC'));
        $this->curl_headers = [
            'Date: ' . $date->format('D, d M Y H:i:s') . ' GMT', // RFC 1123
            'Accept: application/json',
            'Accept-Charset: utf-8',
            'Accept-Encoding: gzip',
            'Cookie: auth_tkt=foo'
        ];

        if ($this->api_key) {
            $this->curl_headers[] = 'Authorization: ' . $this->api_key;
        }
    }

    /**
     * @param $resource
     *
     * @return mixed
     * @throws \CKAN\NotFoundHttpException
     * @throws \Exception
     * @link http://docs.ckan.org/en/latest/api/#ckan.logic.action.create.resource_create
     */
    public function resource_create($resource)
    {
        $data = json_encode($resource, JSON_PRETTY_PRINT);

        return $this->make_request(
            'POST',
            'action/resource_create',
            $data
        );
    }

    /**
     * @param      $method
     * @param      $uri
     * @param null $data
     * @return mixed
     * @throws Exception
     * @throws NotFoundHttpException
     */
    private function make_request($method, $uri, $data = null)
    {
        $method = strtoupper($method);
        if (!in_array($method, ['GET', 'POST'])) {
            throw new Exception('Method ' . $method . ' is not supported');
        }
        // Set cURL URI.
        $url = strpos($uri, '//') ? $uri : $this->api_url . $uri;
        curl_setopt($this->curl_handler, CURLOPT_URL, $url);
        if ($method === 'POST' && $data) {
            curl_setopt($this->curl_handler, CURLOPT_POSTFIELDS, urlencode($data));
        } else {
            $method = 'GET';
        }

        // Set cURL method.
        curl_setopt($this->curl_handler, CURLOPT_CUSTOMREQUEST, $method);

        // Set headers.
        curl_setopt($this->curl_handler, CURLOPT_HTTPHEADER, $this->curl_headers);
        // Execute request and get response headers.
        $response = curl_exec($this->curl_handler);
        $info     = curl_getinfo($this->curl_handler);
        // Check HTTP response code
        if ($info['http_code'] !== 200 && $info['http_code'] !== 201) {
            switch ($info['http_code']) {
                case 0:
                    print_r($info);
                    break;
                case 404:
                    throw new NotFoundHttpException($data);
                    break;
                default:
                    throw new Exception(
                        $info['http_code'] . ': ' .
                        $this->http_status_codes[$info['http_code']] . PHP_EOL . $data . PHP_EOL . $url . PHP_EOL
                    );
            }
        }

        return $response;
    }

    /**
     * Return a list of the site’s tags.
     *
     * @param $data
     *
     * @return mixed
     * @link http://docs.ckan.org/en/latest/api/#ckan.logic.action.get.tag_list
     *  Params:
     *  query (string) – a tag name query to search for, if given only tags whose names contain this string will be
     *     returned (optional) vocabulary_id (string) – the id or name of a vocabulary, if give only tags that belong
     *     to this vocabulary will be returned (optional) all_fields (boolean) – return full tag dictionaries instead
     *     of just names (optional, default: False)
     */
    public function tag_list($data = null)
    {
        return $this->make_request(
            'POST',
            'action/tag_list',
            $data
        );
    }

    /**
     * @param $search
     *
     * @return mixed
     */
    public function api_resource_search($search)
    {
        http: //catalog.data.gov/api/search/resource?url=explore.data.gov&all_fields=1&limit=100

        $query = http_build_query($search);

        return $this->make_request(
            'GET',
            'http://catalog.data.gov/api/search/resource?all_fields=1&limit=100&' . $query
        );
    }

    /**
     * Create a new vocabulary tag.
     *
     * @param      $name
     * @param null $vocabulary_id
     *
     * @return mixed
     * @link     http://docs.ckan.org/en/latest/api/#ckan.logic.action.get.tag_list
     *  Params:
     *  name (string) – the name for the new tag, a string between 2 and 100 characters long containing only
     *  alphanumeric characters and -, _ and ., e.g. 'Jazz'
     *  vocabulary_id (string) – the name or id of the vocabulary that the new tag should be added to, e.g. 'Genre'
     */
    public function tag_create($name, $vocabulary_id)
    {
        $data = [
            'name'          => $name,
            'vocabulary_id' => $vocabulary_id,
        ];
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return $this->make_request(
            'POST',
            'action/tag_create',
            $data
        );
    }

    /**
     * Return a list of all the site’s tag vocabularies.
     */
    public function vocabulary_list()
    {
        return $this->make_request('GET', 'action/vocabulary_list');
    }

    /**
     * Create a new tag vocabulary.
     *
     * @param $name
     *     Params:
     *     name (string) – the name for the new vocabulary, a string between 2 and 100 characters long containing only
     *     alphanumeric characters and -, _ and ., e.g. 'Jazz' tags (list of tag dictionaries) – the new tags to add to
     *     the new vocabulary, for the format of tag dictionaries see tag_create()
     *
     * @return mixed
     */
    public function vocabulary_create($name)
    {
        $data = [
            'name' => $name,
        ];
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return $this->make_request(
            'POST',
            'action/vocabulary_create',
            $data
        );
    }

    /**
     * Return a list of the names of the site’s groups.
     *
     * @param bool $all_fields
     *
     * @return mixed
     * @link http://docs.ckan.org/en/latest/api/index.html#ckan.logic.action.get.group_list
     */
    public function group_list($all_fields = false)
    {
        $solr_request = [
            'all_fields' => $all_fields
        ];
        $data         = json_encode($solr_request, JSON_PRETTY_PRINT);

        return $this->make_request(
            'POST',
            'action/group_list',
            $data
        );
    }

    /**
     * Searches for packages satisfying a given search criteria
     *
     * @param string $package_id (id/name)
     *
     * @return mixed
     * @link http://docs.ckan.org/en/latest/api/index.html#ckan.logic.action.get.package_show
     */
    public function package_show($package_id)
    {
        $solr_request = [
            'id' => $package_id
        ];
        $data         = json_encode($solr_request, JSON_PRETTY_PRINT);

        return $this->make_request(
            'POST',
            'action/package_show',
            $data
        );
    }

    /**
     * Returns organization list
     *
     *
     * @return mixed
     * @link http://docs.ckan.org/en/latest/api/index.html#ckan.logic.action.get.organization_list
     */
    public function organization_list()
    {

        return $this->make_request(
            'POST',
            'action/organization_list'
        );
    }

    /**
     * Returns organization with matching id or name
     *
     * @param string $organization_id (id/name)
     *
     * @return mixed
     * @link http://docs.ckan.org/en/latest/api/index.html#ckan.logic.action.get.organization_show
     */
    public function organization_show($organization_id, $include_datasets = false)
    {
        $solr_request = [
            'id'               => $organization_id,
            'include_datasets' => $include_datasets
        ];
        $data         = json_encode($solr_request, JSON_PRETTY_PRINT);

        return $this->make_request(
            'POST',
            'action/organization_show',
            $data
        );
    }

    /**
     * Returns user with matching id or name
     *
     * @param string $user_id (id/name)
     *
     * @return mixed
     * @link http://docs.ckan.org/en/latest/api/index.html#ckan.logic.action.get.user_show
     */
    public function user_show($user_id)
    {
        $solr_request = [
            'id' => $user_id
        ];
        $data         = json_encode($solr_request, JSON_PRETTY_PRINT);

        return $this->make_request(
            'POST',
            'action/user_show',
            $data
        );
    }

    /**
     * @param $package_id
     *
     * @return mixed
     *
     * @link http://docs.ckan.org/en/latest/api/index.html#ckan.logic.action.delete.package_delete
     *
     * @throws \CKAN\NotFoundHttpException
     * @throws \Exception
     */
    public function package_delete($package_id)
    {
        $solr_request = [
            'id' => $package_id,
        ];
        $data         = json_encode($solr_request, JSON_PRETTY_PRINT);

        return $this->make_request(
            'POST',
            'action/package_delete',
            $data
        );
    }

    /**
     * Searches for packages satisfying a given search criteria
     *
     * @param        $q
     * @param        $fq
     * @param int    $rows
     * @param int    $start
     * @param string $sort
     *
     * @return mixed
     * @link http://docs.ckan.org/en/latest/api/index.html#ckan.logic.action.get.package_search
     */
    public function package_search($q = '', $fq = '', $rows = 100, $start = 0, $sort = 'score desc, name asc')
    {
        $solr_request = [
            'q'     => $q,
            'fq'    => $fq,
            'rows'  => $rows,
            'start' => $start,
            'sort'  => $sort
        ];
        $data         = json_encode($solr_request, JSON_PRETTY_PRINT);

        return $this->make_request(
            'POST',
            'action/package_search',
            $data
        );
    }

    /**
     * @param             $member_id
     * @param string      $object_type ('user', 'package')
     * @param string|bool $capacity    ('member', 'editor', 'admin', 'public', 'private')
     *
     * @return mixed
     *
     * @link http://docs.ckan.org/en/latest/api/#ckan.logic.action.get.member_list
     */
    public function member_list($member_id, $object_type = 'package', $capacity = false)
    {
        $solr_request = [
            'id' => $member_id
        ];
        if ($object_type && ('none' != $object_type)) {
            $solr_request['object_type'] = $object_type;
        }
        if ($capacity) {
            $solr_request['capacity'] = $capacity;
        }
        $data = json_encode($solr_request, JSON_PRETTY_PRINT);

        return $this->make_request(
            'POST',
            'action/member_list',
            $data
        );
    }

    /**
     * Create a dataset (package)
     *
     * @param $data
     *
     * @return mixed
     * @link http://docs.ckan.org/en/latest/api/index.html#ckan.logic.action.update.package_create
     */
    public function package_create($data)
    {
        return $this->make_request(
            'POST',
            'action/package_create',
            $data
        );
    }

    /**
     * Update a dataset (package)
     *
     * @param $data
     *
     * @return mixed
     * @link http://docs.ckan.org/en/latest/api/index.html#ckan.logic.action.update.package_update
     */
    public function package_update(array $data)
    {
        $data = json_encode($data, JSON_PRETTY_PRINT);

        return $this->make_request(
            'POST',
            'action/package_update',
            $data
        );
    }

    /**
     * Since it's possible to leave cURL open, this is the last chance to close it
     */
    public function __destruct()
    {
        if ($this->curl_handler) {
            curl_close($this->curl_handler);
            unset($this->curl_handler);
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function post_package_register($data)
    {
        return $this->make_request('POST', $this->resources['package_register'], $data);
    }

    /**
     * @param $package
     * @param $data
     * @return mixed
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function put_package_entity($package, $data)
    {
        return $this->make_request('POST', $this->resources['package_entity'] . '/' . urlencode($package), $data);
    }
}
