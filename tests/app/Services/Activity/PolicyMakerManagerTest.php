<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\PolicyMaker;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\PolicyMakerManager;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Psr\Log\LoggerInterface;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class PolicyMakerManagerTest
 * @package Test\app\Services\Activity
 */
class PolicyMakerManagerTest extends AidStreamTestCase
{

    protected $activity;
    protected $version;
    protected $policyMakerRepository;
    protected $dbLogger;
    protected $logger;
    protected $auth;
    protected $policyMakerManager;

    public function setup()
    {
        parent::setup();
        $this->activity              = m::mock(Activity::class);
        $this->version               = m::mock(Version::class);
        $this->policyMakerRepository = m::mock(PolicyMaker::class);
        $this->logger                = m::mock(LoggerInterface::class);
        $this->version->shouldReceive('getActivityElement->getPolicyMaker->getRepository')->andReturn(
            $this->policyMakerRepository
        );
        $this->dbLogger           = m::mock(Log::class);
        $this->auth               = m::mock(Guard::class);
        $this->policyMakerManager = new PolicyMakerManager(
            $this->version,
            $this->dbLogger,
            $this->auth,
            $this->logger
        );
    }

    public function testItShouldUpdatePolicyMaker()
    {
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $organizationModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->with('id')->andreturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('policy_maker')->andReturn('policyMaker');
        $this->policyMakerRepository->shouldReceive('update')
                                    ->once()
                                    ->with(
                                        ['policy_maker' => 'policyMaker'],
                                        $activityModel
                                    )
                                    ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Policy Maker Updated!',
            ['for' => 'policyMaker']
        );
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.policy_maker_updated',
            [
                'activity_id'     => 1,
                'organization'    => 'organizationName',
                'organization_id' => 1
            ]
        );
        $this->assertTrue(
            $this->policyMakerManager->update(
                ['policy_maker' => 'policyMaker'],
                $activityModel
            )
        );
    }

    public function testItShouldGetPolicyMakerDataWithCertainId()
    {
        $this->policyMakerRepository->shouldReceive('getPolicyMakerData')
                                    ->with(1)
                                    ->andReturn($this->activity);
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->policyMakerManager->getPolicyMakerData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
