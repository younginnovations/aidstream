<?php

namespace Services\Workflow\Registry;

use \Mockery as m;
use Test\AidStreamTestCase;
use App\Services\Workflow\Registry\RegistryApiHandler;

/**
 * Class RegistryApiHandlerTest
 */
class RegistryApiHandlerTest extends AidStreamTestCase
{
    public function setUp()
    {
        parent::setup();

        $this->registryApiHandler = m::mock(RegistryApiHandler::class);
    }

    /** @test */
    public function itShouldInitializeCkan()
    {
        $this->registryApiHandler->shouldReceive('init')->with('url', 'key')->andReturnSelf();
        $this->assertInstanceOf(RegistryApiHandler::class, $this->registryApiHandler->init('url', 'key'));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
