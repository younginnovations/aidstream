<?php

namespace Test\Smoke\FrontEnd;

use Test\Smoke\AidStreamSmokeTestCase;

/**
 * Class PagesTest
 * @package Test\Smoke\FrontEnd
 */
class PagesTest extends AidStreamSmokeTestCase
{
    /**
     * List all your front-end/public paths below
     *
     * @return array
     */
    public function providerFrontEndPaths()
    {
        return [
            [$this->baseUrl, 200],
            ['auth/login', 200],
            ['auth/register', 200], //fix this bug guys @todo
            // ['not-existing', 404],
            // ['.git', 404],
        ];
    }

    /**
     * @dataProvider providerFrontEndPaths
     */
    public function testPagesAreOk($path, $responseCode)
    {
        $this->printLn(sprintf('Testing page %s to be %s', $path, $responseCode));

        $response = $this->client->get($path, ['exceptions' => false]);
        $this->assertEquals(
            $responseCode,
            $response->getStatusCode(),
            sprintf('Testing response code to be %s for %s as guest user.', $responseCode, $path)
        );
        $this->assertNotNull($response->getBody());
    }
}
