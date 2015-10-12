<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\RecipientRegion;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\RecipientRegionManager;
use App\User;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class RecipientRegionManagerTest
 * @package Test\app\Services\Activity
 */
class RecipientRegionManagerTest extends AidStreamTestCase
{
    protected $activity;
    protected $version;
    protected $auth;
    protected $logger;
    protected $recipientRegionRepo;
    protected $recipientRegionManager;

    public function setup()
    {
        parent::setUp();
        $this->activity            = m::mock(Activity::class);
        $this->version             = m::mock(Version::class);
        $this->auth                = m::mock(Guard::class);
        $this->logger              = m::mock(Log::class);
        $this->recipientRegionRepo = m::mock(RecipientRegion::class);
        $this->version->shouldReceive('getActivityElement->getRecipientRegion->getRepository')->andReturn(
            $this->recipientRegionRepo
        );
        $this->recipientRegionManager = new RecipientRegionManager($this->version, $this->logger, $this->auth);
    }

    public function testItShouldUpdateActivityRecipientRegion()
    {
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $organizationModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->once()->with('recipient_region')->andReturn(
            'recipientRegion'
        );
        $this->recipientRegionRepo->shouldReceive('update')
                                  ->once()
                                  ->with(
                                      ['recipient_region' => 'recipientRegion'],
                                      $activityModel
                                  )
                                  ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Activity Recipient Region Updated!',
            ['for ' => 'recipientRegion']
        );
        $this->logger->shouldReceive('activity')->once()->with(
            'activity.recipient_region_updated',
            [
                'recipientRegion' => 'recipientRegion',
                'organization'    => 'organizationName',
                'organization_id' => 1
            ]
        );
        $this->assertTrue(
            $this->recipientRegionManager->update(
                ['recipient_region' => 'recipientRegion'],
                $activityModel
            )
        );
    }

    public function testItShouldGetRecipientRegionDataWithCertainId()
    {
        $this->recipientRegionRepo->shouldReceive('getRecipientRegionData')
                                  ->with(1)
                                  ->andReturn($this->activity);
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->recipientRegionManager->getRecipientRegionData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
