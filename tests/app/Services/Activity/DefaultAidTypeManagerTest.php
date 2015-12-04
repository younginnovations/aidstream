<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\DefaultAidType;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\DefaultAidTypeManager;
use App\User;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class DefaultAidTypeManagerTest
 * @package Test\app\Services\Activity
 */
class DefaultAidTypeManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $dbLogger;
    protected $logger;
    protected $defaultAidTypeRepo;
    protected $defaultAidTypeManager;
    protected $activity;
    protected $database;

    public function SetUp()
    {
        parent::setUp();
        $this->version            = m::mock(Version::class);
        $this->auth               = m::mock(Guard::class);
        $this->dbLogger           = m::mock(DbLogger::class);
        $this->logger             = m::mock(Logger::class);
        $this->defaultAidTypeRepo = m::mock(DefaultAidType::class);
        $this->activity           = m::mock(Activity::class);
        $this->database           = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getActivityElement->getDefaultAidType->getRepository')->andReturn(
            $this->defaultAidTypeRepo
        );
        $this->defaultAidTypeManager = new DefaultAidTypeManager(
            $this->version,
            $this->auth,
            $this->database,
            $this->dbLogger,
            $this->logger
        );
    }

    public function testItShouldUpdateActivityDefaultAidType()
    {
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldREceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->with('id')->andreturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('default_aid_type')->andReturn(
            'testDefaultAidType'
        );
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->defaultAidTypeRepo->shouldReceive('update')
                                 ->once()
                                 ->with(['default_aid_type' => 'testDefaultAidType'], $activityModel)
                                 ->andReturn(true);
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $this->logger->shouldReceive('info')->once()->with(
            'Activity Default Aid Type updated!',
            ['for' => 'testDefaultAidType']
        );
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.default_aid_type',
            [
                'activity_id'     => 1,
                'organization'    => 'orgName',
                'organization_id' => 1
            ]
        );
        $this->assertTrue(
            $this->defaultAidTypeManager->update(
                ['default_aid_type' => 'testDefaultAidType'],
                $activityModel
            )
        );
    }

    public function testItShouldGetDefaultAidTypeDataWithCertainId()
    {
        $this->defaultAidTypeRepo->shouldReceive('getDefaultAidTypeData')->once()->with(1)->andReturn(
            $this->activity
        );
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->defaultAidTypeManager->getDefaultAidTypeData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
