<?php namespace Test\SuperAdmin\Services;

use App\SuperAdmin\Services\SuperAdminManager;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class SuperAdminManagerTest
 * @package Test\SuperAdmin\Services
 */
class SuperAdminManagerTest extends AidStreamTestCase
{
    protected $adminInterface;
    protected $superAdminManager;

    public function setUp()
    {
        parent::setUp();
        $this->adminInterface    = m::mock('App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin');
        $this->superAdminManager = new SuperAdminManager($this->adminInterface);
    }

    public function testItShouldGetAllOrganizations()
    {
        $this->adminInterface->shouldReceive('getOrganizations')->andReturnSelf();
        $this->assertInstanceOf('App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin', $this->superAdminManager->getOrganizations());
    }

    public function testItShouldGetOrganizationById()
    {
        $this->adminInterface->shouldReceive('getOrganizationById')->once()->with(1)->andReturnSelf();
        $this->assertInstanceOf('App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin', $this->superAdminManager->getOrganizationById(1));
    }

    public function testItShouldGetOrganizationUserDataById()
    {
        $this->adminInterface->shouldReceive('getOrganizationUserById')->once()->with(1)->andReturnSelf();
        $this->assertInstanceOf('App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin', $this->superAdminManager->getOrganizationUserById(1));
    }

    public function testItShouldSaveOrganization()
    {
        $this->adminInterface->shouldReceive('saveOrganization')->once()->with([], 1)->andReturnSelf();
        $this->assertInstanceOf('App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin', $this->superAdminManager->saveOrganization([], 1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
