<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\CollaborationType;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\CollaborationTypeManager;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class CollaborationTypeManagerTest
 * @package Test\app\Services\Activity
 */
class CollaborationTypeManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $dbLogger;
    protected $logger;
    protected $collaborationTypeRepo;
    protected $collaborationTypeManager;
    protected $activity;
    protected $database;

    public function SetUp()
    {
        parent::setUp();
        $this->version               = m::mock(Version::class);
        $this->auth                  = m::mock(Guard::class);
        $this->dbLogger              = m::mock(DbLogger::class);
        $this->logger                = m::mock(Logger::class);
        $this->collaborationTypeRepo = m::mock(CollaborationType::class);
        $this->activity              = m::mock(Activity::class);
        $this->database              = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getActivityElement->getCollaborationType->getRepository')->andReturn(
            $this->collaborationTypeRepo
        );
        $this->collaborationTypeManager = new CollaborationTypeManager(
            $this->version,
            $this->auth,
            $this->database,
            $this->dbLogger,
            $this->logger
        );
    }

    public function testItShouldUpdateActivityCollaborationType()
    {
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldREceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->with('id')->andreturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('collaboration_type')->andReturn(
            'testCollaborationType'
        );
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->collaborationTypeRepo->shouldReceive('update')
                                    ->once()
                                    ->with(['collaboration_type' => 'testCollaborationType'], $activityModel)
                                    ->andReturn(true);
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $this->logger->shouldReceive('info')->once()->with(
            'Activity Collaboration Type updated!',
            ['for' => 'testCollaborationType']
        );
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.collaboration_type',
            [
                'activity_id'     => 1,
                'organization'    => 'orgName',
                'organization_id' => 1
            ]
        );
        $this->assertTrue(
            $this->collaborationTypeManager->update(
                ['collaboration_type' => 'testCollaborationType'],
                $activityModel
            )
        );
    }

    public function testItShouldGetCollaborationTypeDataWithCertainId()
    {
        $this->collaborationTypeRepo->shouldReceive('getCollaborationTypeData')->once()->with(1)->andReturn(
            $this->activity
        );
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->collaborationTypeManager->getCollaborationTypeData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
