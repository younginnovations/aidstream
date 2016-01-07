<?php

namespace Test\Smoke;

use YIPL\Hookah\Test\Smoke\BaseTestCase;

/**
 * Class AidStreamSmokeTestCase
 * @package Test\Smoke
 */
class AidStreamSmokeTestCase extends BaseTestCase
{
    /**
     * @var string
     */
    protected $baseUrl = 'http://newstage.aidstream.org/';

    /**
     * Constructor
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
}
