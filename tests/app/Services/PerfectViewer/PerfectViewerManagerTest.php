<?php

namespace Tests\App\Services\PerfectViewer;

use Mockery as m;
use App\Core\V202\Repositories\PerfectViewer\PerfectViewerRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Logging\Log;
use App\Models\HistoricalExchangeRate;
use Test\AidStreamTestCase;
use App\Services\PerfectViewer\PerfectViewerManager;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Models\Activity\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use App\Models\ActivityPublished;
use App\Models\PerfectViewer\ActivitySnapshot;

/**
 * Class PerfectViewerManagerTest
 */
class PerfectViewerManagerTest extends AidStreamTestCase
{
    protected $perfectViewerRepository;
    protected $databaseManager;
    protected $logger;
    protected $historicalExchangeRate;
    protected $perfectViewerManager;
    protected $organization;
    protected $activityPublished;
    protected $activitySnapshot;

    public function setUp()
    {
        parent::setUp();
        $this->activityPublished = m::mock(ActivityPublished::class);
        $this->supportCollection = m::mock(SupportCollection::class);
        $this->activitySnapshot = m::mock(ActivitySnapshot::class);
        $this->organization = m::mock(Organization::class);
        $this->activity = m::mock(Activity::class);
        $this->perfectViewerRepository = m::mock(PerfectViewerRepository::class);
        $this->databaseManager = m::mock(DatabaseManager::class);
        $this->logger = m::mock(Log::class);
        $this->historicalExchangeRate = m::mock(HistoricalExchangeRate::class);
        $this->perfectViewerManager = new PerfectViewerManager($this->perfectViewerRepository,
            $this->databaseManager,
            $this->logger,
            $this->historicalExchangeRate);
    }

    /** @test */
    public function itShouldCreateSnapshot()
    {
        $transaction = m::mock(Transaction::class);
        $collection = m::mock(Collection::class);
        $arrayIterator = new \ArrayIterator($collection);

        $perfectActivity = [
            "title" => null,
            "description" => null,
            "identifier" => null,
            "other_identifier" => null,
            "activity_date" => null,
            "activity_status" => null,
            "budget" => null,
            "contact_info" => null,
            "updated_at" => null,
            "recipient_country" => null,
            "recipient_region" => null,
            "sector" => null,
            "participating_organization" => null,
            "document_link" => null,
            "reporting_org" => [['reporting_organization_identifier' => 'test-org']],
            "transactions" => [],
            "totalBudget" => [
            "value" => 0,
            "currency" => ""
            ]
        ];

        $data = [
            'published_data' => $perfectActivity,
            'org_id' => 1,
            'activity_id' => 1,
            'activity_in_registry' => 1,
            'filename' => 'abc.xml'
        ];

        $transactionTotals = [
            'total_incoming_funds' => 0,
            'total_commitments' => 0,
            'total_disbursements' => 0,
            'total_expenditures' => 0
        ];

        $perfectOrg = [
            'org_id' => 1,
            'published_to_registry' => true,
            'org_slug' => 'test-org',
            'transaction_totals' => $transactionTotals
        ];


        $this->perfectViewerRepository->shouldReceive('getExchangeRatesBuilder')->andReturn($this->historicalExchangeRate);
        $this->activity->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $this->activity->shouldReceive('getAttribute')->with('default_field_values')->andReturn($collection);
        $this->activity->shouldReceive('getAttribute')->with('organization_id')->andReturn(1);
        $this->activity->shouldReceive('getAttribute')->with('published_to_registry')->andReturn(1);
        $this->activity->shouldReceive('getAttribute')->with('title')->andReturn($this->activity->title);
        $this->activity->shouldReceive('getAttribute')->with('description')->andReturn($this->activity->description);
        $this->activity->shouldReceive('getAttribute')->with('identifier')->andReturn($this->activity->identifier);
        $this->activity->shouldReceive('getAttribute')->with('other_identifier')->andReturn($this->activity->other_identifier);
        $this->activity->shouldReceive('getAttribute')->with('activity_date')->andReturn($this->activity->activity_date);
        $this->activity->shouldReceive('getAttribute')->with('activity_status')->andReturn($this->activity->activity_status);
        $this->activity->shouldReceive('getAttribute')->with('budget')->andReturn($this->activity->budget);
        $this->activity->shouldReceive('getAttribute')->with('contact_info')->andReturn($this->activity->contact_info);
        $this->activity->shouldReceive('getAttribute')->with('updated_at')->andReturn($this->activity->updated_at);
        $this->activity->shouldReceive('getAttribute')->with('recipient_country')->andReturn($this->activity->recipient_country);
        $this->activity->shouldReceive('getAttribute')->with('recipient_region')->andReturn($this->activity->recipient_region);
        $this->activity->shouldReceive('getAttribute')->with('sector')->andReturn($this->activity->sector);
        $this->activity->shouldReceive('getAttribute')->with('participating_organization')->andReturn($this->activity->participating_organization);
        $this->activity->shouldReceive('getAttribute')->with('document_link')->andReturn($this->activity->document_link);

        $this->perfectViewerRepository->shouldReceive('getOrganization')->with(1)->andReturn($this->organization);
        $this->perfectViewerRepository->shouldReceive('getActivityTransactions')->with(1)->andReturn($transaction);
        $this->perfectViewerRepository->shouldReceive('getPublishedFileName')->andReturn($this->activityPublished);
        $this->perfectViewerRepository->shouldReceive('storeActivity')->with($data)->andReturn($this->activitySnapshot);

        $this->organization->shouldReceive('where->with->first')->andReturn($this->organization);
        $this->organization->shouldReceive('getAttribute')->with('activities')->andReturn($arrayIterator);
        $this->activity->shouldReceive('getAttribute')->with('transactions')->andReturn($arrayIterator);
        $this->supportCollection->shouldReceive('toArray')->andReturn([]);
        $this->perfectViewerRepository->shouldReceive('getTransactions')->with(1)->andReturn($this->supportCollection);

        $this->databaseManager->shouldReceive('beginTransaction');
        $this->databaseManager->shouldReceive('commit');

        $this->activityPublished->shouldReceive('getAttribute')->with('filename')->andReturn('abc.xml');

        $this->activity->shouldReceive('toArray')->andReturn([]);
        $this->organization->shouldReceive('toArray')->andReturn([['id' => 1, 'published_to_registry' => true, 'reporting_org' => [['reporting_organization_identifier' => 'test-org']], $transactionTotals]]);
        $transaction->shouldReceive('toArray')->andReturn([]);

        $this->logger->shouldReceive('info')->with(
                'Activity snapshot has been added',
                [
                    ' of activity '      => 1,
                    ' and organization ' => 1
                ]);

        //Organization
        $this->perfectViewerRepository->shouldReceive('storeOrganization')->with($perfectOrg);

        $this->assertInstanceOf(perfectViewerManager::class, $this->perfectViewerManager->createSnapshot($this->activity));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
