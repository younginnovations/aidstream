<?php namespace Test\app\Services\Wizard\Activity;

use App\Core\V201\Wizard\Repositories\Activity\StepTwo;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Wizard\Activity\StepTwoManager;
use App\User;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class StepTwoManagerTest
 * @package Test\app\Services\Wizard\Activity
 */
class StepTwoManagerTest extends AidStreamTestCase
{
    protected $stepTwoManager;
    protected $auth;
    protected $version;
    protected $logger;
    protected $stepTwoRepo;
    protected $activity;
    protected $database;

    public function setUp()
    {
        parent::setUp();
        $this->version     = m::mock(Version::class);
        $this->auth        = m::mock(Guard::class);
        $this->logger      = m::mock(Log::class);
        $this->stepTwoRepo = m::mock(StepTwo::class);
        $this->activity    = m::mock(Activity::class);
        $this->database    = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getActivityElement->getStepTwo->getRepository')->andReturn(
            $this->stepTwoRepo
        );
        $this->stepTwoManager = new StepTwoManager($this->version, $this->auth, $this->database, $this->logger);
    }

    public function testItShouldUpdateStepTwoActivityElementUsingWizardView()
    {
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->once()->with('title')->andReturn('testTitle');
        $activityModel->shouldReceive('getAttribute')->once()->with('description')->andReturn('testDescription');
        $this->stepTwoRepo->shouldReceive('update')
                          ->once()
                          ->with(['title' => 'testTitle', 'description' => 'testDescription'], $activityModel)
                          ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Step Two Completed!',
            ['for' => ['testTitle', 'testDescription']]
        );
        $this->logger->shouldReceive('activity')->once()->with(
            'activity.step_two_completed',
            [
                'title'           => 'testTitle',
                'description'     => 'testDescription',
                'organization'    => 'orgName',
                'organization_id' => 1
            ]
        );
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $this->assertTrue(
            $this->stepTwoManager->update(
                ['title' => 'testTitle', 'description' => 'testDescription'],
                $activityModel
            )
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
