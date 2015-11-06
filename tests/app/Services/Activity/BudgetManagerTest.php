<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\Budget;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\BudgetManager;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Psr\Log\LoggerInterface;
use Test\AidStreamTestCase;
use Mockery as m;


class BudgetManagerTest extends AidStreamTestCase
{

    protected $activity;
    protected $version;
    protected $budgetRepository;
    protected $dbLogger;
    protected $logger;
    protected $auth;
    protected $budgetManager;

    public function setup()
    {
        parent::setup();
        $this->activity         = m::mock(Activity::class);
        $this->version          = m::mock(Version::class);
        $this->budgetRepository = m::mock(Budget::class);
        $this->logger           = m::mock(LoggerInterface::class);
        $this->version->shouldReceive('getActivityElement->getBudget->getRepository')->andReturn(
            $this->budgetRepository
        );
        $this->dbLogger      = m::mock(Log::class);
        $this->auth          = m::mock(Guard::class);
        $this->budgetManager = new BudgetManager(
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
        $activityModel->shouldReceive('getAttribute')->once()->with('budget')->andReturn('budget');
        $this->budgetRepository->shouldReceive('update')
                               ->once()
                               ->with(
                                   ['budget' => 'budget'],
                                   $activityModel
                               )
                               ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Budget Updated!',
            ['for' => 'budget']
        );
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.budget_updated',
            [
                'budget'          => 'budget',
                'organization'    => 'organizationName',
                'organization_id' => 1
            ]
        );
        $this->assertTrue(
            $this->budgetManager->update(
                ['budget' => 'budget'],
                $activityModel
            )
        );
    }

    public function testItShouldGetPolicyMakerDataWithCertainId()
    {
        $this->budgetRepository->shouldReceive('getBudgetData')
                               ->with(1)
                               ->andReturn($this->activity);
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->budgetManager->getBudgetData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
