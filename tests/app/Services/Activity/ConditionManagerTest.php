<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\Condition;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\ConditionManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Psr\Log\LoggerInterface;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class ConditionManagerTest
 * @package Test\app\Services\Activity
 */
class ConditionManagerTest extends AidStreamTestCase
{

    /**
     * @var
     */
    protected $activity;
    /**
     * @var
     */
    protected $version;
    /**
     * @var
     */
    protected $conditionRepository;
    /**
     * @var
     */
    protected $dbLogger;
    /**
     * @var
     */
    protected $logger;
    /**
     * @var
     */
    protected $auth;
    /**
     * @var
     */
    protected $conditionManager;

    public function setup()
    {
        parent::setup();
        $this->activity            = m::mock(Activity::class);
        $this->version             = m::mock(Version::class);
        $this->conditionRepository = m::mock(Condition::class);
        $this->logger              = m::mock(LoggerInterface::class);
        $this->version->shouldReceive('getActivityElement->getCondition->getRepository')->andReturn(
            $this->conditionRepository
        );
        $this->dbLogger         = m::mock(Log::class);
        $this->auth             = m::mock(Guard::class);
        $this->conditionManager = new ConditionManager($this->version, $this->dbLogger, $this->auth, $this->logger);
    }

    public function testItShouldUpdateCondition()
    {
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $organizationModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->with('id')->andreturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('condition')->andReturn('condition');
        $this->conditionRepository->shouldReceive('update')->once()->with(['condition' => 'condition'], $activityModel)->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with('Condition Updated!', ['for' => 'condition']);
        $this->dbLogger->shouldReceive('activity')->once()->with('activity.condition_updated', ['activity_id' => 1, 'organization' => 'organizationName', 'organization_id' => 1]);
        $this->assertTrue($this->conditionManager->update(['condition' => 'condition'], $activityModel));
    }

    public function testItShouldGetConditionDataWithCertainId()
    {
        $this->conditionRepository->shouldReceive('getConditionData')->with(1)->andReturn($this->activity);
        $this->assertInstanceOf('App\Models\Activity\Activity', $this->conditionManager->getConditionData(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
