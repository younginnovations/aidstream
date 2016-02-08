<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\PolicyMarker;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\PolicyMarkerManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Psr\Log\LoggerInterface;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class PolicyMarkerManagerTest
 * @package Test\app\Services\Activity
 */
class PolicyMarkerManagerTest extends AidStreamTestCase
{

    protected $activity;
    protected $version;
    protected $policyMarkerRepository;
    protected $dbLogger;
    protected $logger;
    protected $auth;
    protected $policyMarkerManager;

    public function setup()
    {
        parent::setup();
        $this->activity               = m::mock(Activity::class);
        $this->version                = m::mock(Version::class);
        $this->policyMarkerRepository = m::mock(PolicyMarker::class);
        $this->logger                 = m::mock(LoggerInterface::class);
        $this->version->shouldReceive('getActivityElement->getPolicyMarker->getRepository')->andReturn(
            $this->policyMarkerRepository
        );
        $this->dbLogger            = m::mock(Log::class);
        $this->auth                = m::mock(Guard::class);
        $this->policyMarkerManager = new PolicyMarkerManager(
            $this->version,
            $this->dbLogger,
            $this->auth,
            $this->logger
        );
    }

    public function testItShouldUpdatePolicyMarker()
    {
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $organizationModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->with('id')->andreturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('policy_marker')->andReturn('policyMarker');
        $this->policyMarkerRepository->shouldReceive('update')
                                     ->once()
                                     ->with(
                                         ['policy_marker' => 'policyMarker'],
                                         $activityModel
                                     )
                                     ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Policy Marker Updated!',
            ['for' => 'policyMarker']
        );
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.policy_marker_updated',
            [
                'activity_id'     => 1,
                'organization'    => 'organizationName',
                'organization_id' => 1
            ]
        );
        $this->assertTrue(
            $this->policyMarkerManager->update(
                ['policy_marker' => 'policyMarker'],
                $activityModel
            )
        );
    }

    public function testItShouldGetPolicyMarkerDataWithCertainId()
    {
        $this->policyMarkerRepository->shouldReceive('getPolicyMarkerData')
                                     ->with(1)
                                     ->andReturn($this->activity);
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->policyMarkerManager->getPolicyMarkerData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
