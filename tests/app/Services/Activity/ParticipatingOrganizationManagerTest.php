<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\ParticipatingOrganization;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\ParticipatingOrganizationManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class ParticipatingOrganizationManagerTest
 * @package Test\app\Services\Activity
 */
class ParticipatingOrganizationManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $logger;
    protected $auth;
    protected $participatingOrgRepository;
    protected $participatingOrgManager;

    public function setUp()
    {
        parent::setUp();
        $this->version                    = m::mock('App\Core\Version');
        $this->participatingOrgRepository = m::mock(ParticipatingOrganization::class);
        $this->version->shouldReceive('getActivityElement->getParticipatingOrganization->getRepository')->andReturn($this->participatingOrgRepository);
        $this->logger                  = m::mock('Illuminate\Contracts\Logging\Log');
        $this->auth                    = m::mock('Illuminate\Auth\Guard');
        $this->participatingOrgManager = new ParticipatingOrganizationManager(
            $this->version,
            $this->logger,
            $this->auth
        );
    }

    public function testItShouldUpdateParticipatingOrganizationData()
    {
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $organizationModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = m::mock(Activity::class);
        $activityModel->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('participating_organization')->andReturn(
            'participatingOrgName'
        );
        $this->participatingOrgRepository->shouldReceive('update')
                                         ->once()
                                         ->with(
                                             ['participating_organization' => 'participatingOrganizationName'],
                                             $activityModel
                                         )
                                         ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            "Activity Participating Organization updated!",
            ['for' => 'participatingOrgName']
        );
        $this->logger->shouldReceive('activity')->once()->with(
            'activity.participating_organization',
            [
                'activity_id'     => 1,
                'organization'    => 'organizationName',
                'organization_id' => 1
            ]
        );
        $this->assertTrue(
            $this->participatingOrgManager->update(
                ['participating_organization' => 'participatingOrganizationName'],
                $activityModel
            )
        );
    }

    public function testItShouldGetActivityParticipatingOrganizationDataWithCertainId()
    {
        $this->participatingOrgRepository->shouldReceive('getParticipatingOrganizationData')
                                         ->with(1)
                                         ->andReturn(m::mock(Activity::class));
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->participatingOrgManager->getParticipatingOrganizationData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
