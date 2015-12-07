<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\CountryBudgetItem;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\CountryBudgetItemManager;
use App\User;
use Illuminate\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class CountryBudgetItemManagerTest
 * @package Test\app\Services\Activity
 */
class CountryBudgetItemManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $logger;
    protected $countryBudgetItemRepo;
    protected $countryBudgetItemManager;
    protected $activity;
    protected $database;

    public function SetUp()
    {
        parent::setUp();
        $this->version               = m::mock(Version::class);
        $this->auth                  = m::mock(Guard::class);
        $this->logger                = m::mock(Log::class);
        $this->countryBudgetItemRepo = m::mock(CountryBudgetItem::class);
        $this->activity              = m::mock(Activity::class);
        $this->database              = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getActivityElement->getCountryBudgetItem->getRepository')->andReturn(
            $this->countryBudgetItemRepo
        );
        $this->countryBudgetItemManager = new CountryBudgetItemManager(
            $this->version,
            $this->auth,
            $this->database,
            $this->logger
        );
    }

    public function testItShouldUpdateActivityCountryBudgetItem()
    {
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldREceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->with('id')->andreturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('country_budget_items')->andReturn(
            'testCountryBudgetItems'
        );
        $this->countryBudgetItemRepo->shouldReceive('update')
                                    ->once()
                                    ->with(['country_budget_item' => 'testCountryBudgetItem'], $activityModel)
                                    ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Activity Country Budget Items updated!',
            ['for' => 'testCountryBudgetItems']
        );
        $this->logger->shouldReceive('activity')->once()->with(
            'activity.country_budget_items',
            [
                'activity_id'     => 1,
                'organization'    => 'orgName',
                'organization_id' => 1
            ]
        );
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $this->assertTrue(
            $this->countryBudgetItemManager->update(
                ['country_budget_item' => 'testCountryBudgetItem'],
                $activityModel
            )
        );
    }

    public function testItShouldGetCountryBudgetItemDataWithCertainId()
    {
        $this->countryBudgetItemRepo->shouldReceive('getCountryBudgetItemData')->once()->with(1)->andReturn($this->activity);
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->countryBudgetItemManager->getCountryBudgetItemData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
