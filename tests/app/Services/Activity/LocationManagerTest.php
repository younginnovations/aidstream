<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\Location;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\LocationManager;
use Illuminate\Contracts\Logging\Log;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class LocationManagerTest
 * @package Test\app\Services\Activity
 */
class LocationManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $logger;
    protected $auth;
    protected $locationRepository;
    protected $locationManager;

    public function setUp()
    {
        parent::setUp();
        $this->version                    = m::mock('App\Core\Version');
        $this->locationRepository = m::mock(Location::class);
        $this->version->shouldReceive('getActivityElement->getLocation->getRepository')->andReturn($this->locationRepository);
        $this->logger                  = m::mock(Log::class);
        $this->auth                    = m::mock('Illuminate\Auth\Guard');
        $this->locationManager = new LocationManager(
            $this->version,
            $this->logger,
            $this->auth
        );
    }

    public function testItShouldUpdateActivityLocationData()
    {
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $organizationModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = m::mock(Activity::class);
        $activityModel->shouldReceive('getAttribute')->once()->with('location')->andReturn(
            'testLocations'
        );
        $this->locationRepository->shouldReceive('update')
                                         ->once()
                                         ->with(
                                             ['location' => 'testLocation'],
                                             $activityModel
                                         )
                                         ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            "Activity Location Updated!",
            ['for' => 'testLocations']
        );
        $this->logger->shouldReceive('activity')->once()->with(
            'activity.location_updated',
            [
                'location' => 'testLocation',
                'organization'              => 'organizationName',
                'organization_id'           => 1
            ]
        );
        $this->assertTrue(
            $this->locationManager->update(
                ['location' => 'testLocation'],
                $activityModel
            )
        );
    }

    public function testItShouldGetActivityLocationDataWithCertainId()
    {
        $this->locationRepository->shouldReceive('getLocation')
                                         ->with(1)
                                         ->andReturn(m::mock(Activity::class));
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->locationManager->getLocation(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
