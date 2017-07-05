<?php namespace Test\app\Services\Wizard\Activity;

use App\Core\V201\Wizard\Repositories\Activity\StepThree;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Wizard\Activity\StepThreeManager;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class StepThreeManagerTest
 * @package Test\app\Services\Wizard\Activity
 */
class StepThreeManagerTest extends AidStreamTestCase
{
    protected $stepThreeManager;
    protected $auth;
    protected $version;
    protected $logger;
    protected $stepThreeRepo;
    protected $activity;
    protected $database;

    public function setUp()
    {
        parent::setUp();
        $this->version       = m::mock(Version::class);
        $this->auth          = m::mock(Guard::class);
        $this->logger        = m::mock(Log::class);
        $this->stepThreeRepo = m::mock(StepThree::class);
        $this->activity      = m::mock(Activity::class);
        $this->database      = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getActivityElement->getStepThree->getRepository')->andReturn(
            $this->stepThreeRepo
        );
        $this->stepThreeManager = new StepThreeManager($this->version, $this->auth, $this->database, $this->logger);
    }

    /**
     * @test
     */
    public function testItShouldUpdateStepTwoActivityElementUsingWizardView()
    {
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->once()->with('activity_status')->andReturn('testStatus');
        $activityModel->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('activity_date')->andReturn('testDate');
        $this->stepThreeRepo->shouldReceive('update')
                            ->once()
                            ->with(['activity_status' => 'testStatus', 'activity_date' => 'testDate'], $activityModel)
                            ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Step Three Completed!',
            ['for' => ['testStatus', 'testDate']]
        );
        $this->logger->shouldReceive('activity')->once()->with(
            'activity.step_three_completed',
            [
                'activity_id'     => 1,
                'organization'    => 'orgName',
                'organization_id' => 1
            ]
        );
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $this->assertTrue(
            $this->stepThreeManager->update(
                ['activity_status' => 'testStatus', 'activity_date' => 'testDate'],
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
