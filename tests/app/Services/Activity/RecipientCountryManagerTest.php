<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\RecipientCountry;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\RecipientCountryManager;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class RecipientCountryManagerTest
 * @package Test\app\Services\Activity
 */
class RecipientCountryManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $logger;
    protected $auth;
    protected $recipientCountryRepository;
    protected $recipientCountryManager;
    protected $activity;

    public function setUp()
    {
        parent::setUp();
        $this->activity                   = m::mock(Activity::class);
        $this->version                    = m::mock(Version::class);
        $this->recipientCountryRepository = m::mock(RecipientCountry::class);
        $this->version->shouldReceive('getActivityElement->getRecipientCountry->getRepository')->andReturn(
            $this->recipientCountryRepository
        );
        $this->logger                  = m::mock(Log::class);
        $this->auth                    = m::mock(Guard::class);
        $this->recipientCountryManager = new RecipientCountryManager(
            $this->version,
            $this->logger,
            $this->auth
        );
    }

    public function testItShouldUpdateRecipientCountry()
    {
        $organizationModel = m::mock(Organization::class);
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $organizationModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->once()->with('recipient_country')->andReturn(
            'recipientCountry'
        );
        $this->recipientCountryRepository->shouldReceive('update')
                                         ->once()
                                         ->with(
                                             ['recipient_country' => 'recipientCountry'],
                                             $activityModel
                                         )
                                         ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Recipient Country  Updated!',
            ['for ' => 'recipientCountry']
        );
        $this->logger->shouldReceive('activity')->once()->with(
            'activity.recipient_country_updated',
            [
                'recipientCountry' => 'recipientCountry',
                'organization'     => 'organizationName',
                'organization_id'  => 1
            ]
        );
        $this->assertTrue(
            $this->recipientCountryManager->update(
                ['recipient_country' => 'recipientCountry'],
                $activityModel
            )
        );
    }

    public function testItShouldGetRecipientCountryDataWithCertainId()
    {
        $this->recipientCountryRepository->shouldReceive('getRecipientCountryData')
                                         ->with(1)
                                         ->andReturn($this->activity);
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->recipientCountryManager->getRecipientCountryData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
