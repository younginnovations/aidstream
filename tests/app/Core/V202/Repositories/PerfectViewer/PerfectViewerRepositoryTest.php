<?php

namespace Core\V202\Repositories\PerfectViewer;

use App\Models\Activity\Activity;
use App\Models\PerfectViewer\ActivitySnapshot;
use App\Models\ActivityPublished;
use App\Models\Activity\Transaction;
use App\Models\PerfectViewer\OrganizationSnapshot;
use App\Models\HistoricalExchangeRate;
use App\Models\Organization\Organization;
use Mockery as m;
use Test\AidStreamTestCase;
use App\Core\V202\Repositories\PerfectViewer\PerfectViewerRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Query\Builder;

/**
 * Class PerfectViewerRepositoryTest
 */
class PerfectViewerRepositoryTest extends AidStreamTestCase
{
    protected $transaction;
    protected $organization;
    protected $activitySnapshot;
    protected $activityPublished;
    protected $organizationSnapshot;
    protected $historicalExchangeRate;
    protected $perfectViewerRepository;
    protected $builder;
    protected $collection;

    public function setUp()
    {
        parent::setUp();
        $this->builder = m::mock(Builder::class);
        $this->collection = m::mock(Collection::class);
        $this->activitySnapshot = m::mock(ActivitySnapshot::class);
        $this->activityPublished = m::mock(ActivityPublished::class);
        $this->transaction = m::mock(Transaction::class);
        $this->organization = m::mock(Organization::class);
        $this->organizationSnapshot = m::mock(OrganizationSnapshot::class);
        $this->historicalExchangeRate = m::mock(HistoricalExchangeRate::class);
        $this->perfectViewerRepository = new PerfectViewerRepository($this->activitySnapshot,
            $this->activityPublished,
            $this->transaction,
            $this->organization,
            $this->organizationSnapshot,
            $this->historicalExchangeRate);
    }

    /** @test */
    public function itShouldEitherUpdateOrCreateActivitySnapshot()
    {
        $data = ['org_id' => 1,
            'activity_id' => 1
        ];

        $this->activitySnapshot->shouldReceive('updateOrCreate')->once()->with(['activity_id' => $data['activity_id'], 'org_id' => $data['org_id']], $data)->andReturn($this->activitySnapshot);
        $this->assertInstanceOf(ActivitySnapshot::class, $this->perfectViewerRepository->storeActivity($data));
    }

    /** @test */
    public function itShouldUpdateOrCreateOrganizationSnapshot()
    {
        $data = ['org_id' => 1];
        $this->organizationSnapshot->shouldReceive('updateOrCreate')->once()->with(['org_id' => $data['org_id']], $data)->andReturn($this->organizationSnapshot);
        $this->assertInstanceOf(OrganizationSnapshot::class, $this->perfectViewerRepository->storeOrganization($data));
    }

    /** @test */
    public function itShouldReturnPublishedFilesName()
    {
        $this->activityPublished->shouldReceive('where->orderBy->first')->andReturn($this->activityPublished);
        $this->assertInstanceOf(ActivityPublished::class, $this->perfectViewerRepository->getPublishedFileName(1));
    }

    /** @test */
    public function itShouldReturnAllTransactionsOfAnOrganization()
    {
        $activity = m::mock(Activity::class);
        $arrayIterator = new \ArrayIterator($this->collection);

        $this->organization->shouldReceive('where->with->first')->andReturn($this->organization);
        $this->organization->shouldReceive('getAttribute')->with('activities')->andReturn($arrayIterator);
        $activity->shouldReceive('getAttribute')->with('transactions')->andReturn($arrayIterator);

        $this->assertInstanceOf(SupportCollection::class, $this->perfectViewerRepository->getTransactions(1));
    }

    /** @test */
    public function itShouldReturnOrganizationFromOrgId()
    {
        $this->organization->shouldReceive('where->get')->andReturn($this->organization);
        $this->assertInstanceOf(Organization::class, $this->perfectViewerRepository->getOrganization(1));
    }

    /** @test */
    public function itShouldReturnQueryBuilderWithOrganizationAndOrganizationSnapshot()
    {
        $this->organizationSnapshot->shouldReceive('join')->andReturn($this->builder);
        $this->assertInstanceOf('Illuminate\Database\Query\Builder', $this->perfectViewerRepository->organizationQueryBuilder());
    }

    /** @test */
    public function itShouldReturnActivityQueryBuilder()
    {
        $this->organizationSnapshot->shouldReceive('join')->andReturn($this->builder);
        $this->assertInstanceOf('Illuminate\Database\Query\Builder', $this->perfectViewerRepository->organizationQueryBuilder());
    }

    /** @test */
    public function itShouldReturnActivitySnapshot()
    {
        $this->activitySnapshot->shouldReceive('where->get')->andReturn($this->collection);
        $this->assertInstanceOf(Collection::class, $this->perfectViewerRepository->getSnapshot(1));
    }

    /** @test */
    public function itShouldReturnOrganzationFromOrganizationIdentifier()
    {
        $this->organization->shouldReceive('where->get')->andReturn($this->organization);
        $this->assertInstanceOf(Organization::class, $this->perfectViewerRepository->getOrgWithId(1));
    }

    /** @test */
    public function itShouldReturnHistoricalExchangeRateQueryBuilder()
    {
        $this->assertInstanceOf(HistoricalExchangeRate::class, $this->perfectViewerRepository->getExchangeRatesBuilder());
    }

    /** @test */
    public function itShouldReturnTransactionOfAnActivityGivenActivityId()
    {
        $this->transaction->shouldReceive('where->get')->andReturn($this->collection);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->perfectViewerRepository->getActivityTransactions(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
