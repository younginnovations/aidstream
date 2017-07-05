<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\RelatedActivity;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\RelatedActivityManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Psr\Log\LoggerInterface;
use Test\AidStreamTestCase;
use Mockery as m;

/**
 * Class RelatedActivityManagerTest
 * @package Test\app\Services\Activity
 */
class RelatedActivityManagerTest extends AidStreamTestCase
{

    protected $activity;
    protected $version;
    protected $relatedActivityRepository;
    protected $dbLogger;
    protected $logger;
    protected $auth;
    protected $relatedActivityManager;

    public function setup()
    {
        parent::setup();
        $this->activity                  = m::mock(Activity::class);
        $this->version                   = m::mock(Version::class);
        $this->relatedActivityRepository = m::mock(RelatedActivity::class);
        $this->logger                    = m::mock(LoggerInterface::class);
        $this->version->shouldReceive('getActivityElement->getRelatedActivity->getRepository')->andReturn(
            $this->relatedActivityRepository
        );
        $this->dbLogger               = m::mock(Log::class);
        $this->auth                   = m::mock(Guard::class);
        $this->relatedActivityManager = new RelatedActivityManager(
            $this->version,
            $this->dbLogger,
            $this->auth,
            $this->logger
        );
    }

    /**
     * @test
     */
    public function testItShouldUpdateRelatedActivity()
    {
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $organizationModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->with('id')->andreturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('related_activity')->andReturn('relatedActivity');
        $this->relatedActivityRepository->shouldReceive('update')
                                        ->once()
                                        ->with(
                                            ['related_activity' => 'relatedActivity'],
                                            $activityModel
                                        )
                                        ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Related Activity Updated!',
            ['for' => 'relatedActivity']
        );
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.related_activity_updated',
            [
                'activity_id'     => 1,
                'organization'    => 'organizationName',
                'organization_id' => 1
            ]
        );
        $this->assertTrue(
            $this->relatedActivityManager->update(
                ['related_activity' => 'relatedActivity'],
                $activityModel
            )
        );
    }

    /**
     * @test
     */
    public function testItShouldGetRelatedActivityDataWithCertainId()
    {
        $this->relatedActivityRepository->shouldReceive('getRelatedActivityData')
                                        ->with(1)
                                        ->andReturn($this->activity);
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->relatedActivityManager->getRelatedActivityData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
