<?php namespace Test\app\Services\Wizard\Activity;

use App\Core\V201\Wizard\Repositories\Activity\ActivityRepository;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Wizard\Activity\ActivityManager;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class ActivityManagerTest
 * @package Test\app\Services\Wizard\Activity
 */
class ActivityManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $logger;
    protected $activityRepo;
    protected $activity;
    protected $database;
    protected $activityManager;

    public function setUp()
    {
        parent::setUp();
        $this->version      = m::mock(Version::class);
        $this->auth         = m::mock(Guard::class);
        $this->logger       = m::mock(Log::class);
        $this->activityRepo = m::mock(ActivityRepository::class);
        $this->activity     = m::mock(Activity::class);
        $this->database     = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getActivityElement->getWizardRepository')->andReturn(
            $this->activityRepo
        );
        $this->activityManager = new ActivityManager($this->version, $this->auth, $this->database, $this->logger);
    }

    public function testItShouldStoreNewActivityInDatabaseUsingWizardView()
    {
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldREceive('getAttribute')->twice()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->times(3)->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->times(3)->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->with('activity_identifier')->andReturn(
            'testActivityIdentifier'
        );
        $this->activityRepo->shouldReceive('store')
                           ->once()
                           ->with(['activity_identifier' => 'testActivityIdentifier'], ['defaultFieldValues'], 1)
                           ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Activity identifier added!',
            ['for' => 'testActivityIdentifier']
        );
        $this->logger->shouldReceive('activity')->once()->with(
            'activity.activity_added',
            [
                'identifier'      => 'testActivityIdentifier',
                'organization'    => 'orgName',
                'organization_id' => 1
            ]
        );
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $this->assertTrue($this->activityManager->store(['activity_identifier' => 'testActivityIdentifier'], ['defaultFieldValues']));
    }

    public function testItShouldGetActivityDataWithSpecificActivityId()
    {
        $this->activityRepo->shouldReceive('getActivityData')->once()->with(1)->andReturn($this->activity);
        $this->assertInstanceOf('App\Models\Activity\Activity', $this->activityManager->getActivityData(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
