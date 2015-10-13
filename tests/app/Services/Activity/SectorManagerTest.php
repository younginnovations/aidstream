<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\Sector;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\SectorManager;
use App\User;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class SectorManagerTest
 * @package Test\app\Services\Activity
 */
class SectorManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $logger;
    protected $sectorRepository;
    protected $sectorManager;
    protected $activity;

    public function SetUp()
    {
        parent::setUp();
        $this->version          = m::mock(Version::class);
        $this->auth             = m::mock(Guard::class);
        $this->logger           = m::mock(Log::class);
        $this->sectorRepository = m::mock(Sector::class);
        $this->activity         = m::mock(Activity::class);
        $this->version->shouldReceive('getActivityElement->getSector->getRepository')->andReturn(
            $this->sectorRepository
        );
        $this->sectorManager = new SectorManager($this->version, $this->auth, $this->logger);
    }

    public function testItShouldUpdateActivitySector()
    {
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->once()->with('sector')->andReturn(
            'testSector'
        );
        $this->sectorRepository->shouldReceive('update')
                               ->once()
                               ->with(['sector' => 'testSector'], $activityModel)
                               ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Activity Sector updated!',
            ['for' => 'testSector']
        );
        $this->logger->shouldReceive('activity')->once()->with(
            'activity.sector_updated',
            [
                'sector'          => 'testSector',
                'organization'    => 'orgName',
                'organization_id' => 1
            ]
        );
        $this->assertTrue(
            $this->sectorManager->update(
                ['sector' => 'testSector'],
                $activityModel
            )
        );
    }

    public function testItShouldGetSectorDataWithCertainId()
    {
        $this->sectorRepository->shouldReceive('getSectorData')->once()->with(1)->andReturn($this->activity);
        $this->assertInstanceOf('App\Models\Activity\Activity', $this->sectorManager->getSectorData(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
