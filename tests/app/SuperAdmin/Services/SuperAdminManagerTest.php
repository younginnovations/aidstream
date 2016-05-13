<?php namespace Test\SuperAdmin\Services;

use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\OrganizationPublished;
use App\Services\Export\CsvGenerator;
use App\SuperAdmin\Services\SuperAdminManager;
use App\User;
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
    protected $generator;
    protected $user;
    protected $activityPublished;
    protected $organizationPublished;
    protected $activity;

    public function setUp()
    {
        parent::setUp();
        $this->adminInterface        = m::mock('App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin');
        $this->generator             = m::mock(CsvGenerator::class);
        $this->user                  = m::mock(User::class);
        $this->activityPublished     = m::mock(ActivityPublished::class);
        $this->organizationPublished = m::mock(OrganizationPublished::class);
        $this->activity              = m::mock(Activity::class);

        $this->superAdminManager = new SuperAdminManager($this->adminInterface, $this->generator, $this->user, $this->activityPublished, $this->organizationPublished, $this->activity);
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
