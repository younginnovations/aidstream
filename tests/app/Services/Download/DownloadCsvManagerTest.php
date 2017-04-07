<?php

namespace Services\Download;

use App\Core\Version;
use \Mockery as m;
use Test\AidStreamTestCase;
use App\Core\V201\Element\DownloadCsv;
use App\Core\V201\Formatter\CompleteCsvDataFormatter;
use App\Core\V201\Formatter\SimpleCsvDataFormatter;
use App\Core\V201\Formatter\TransactionCsvDataFormatter;
use App\Core\V201\Repositories\DownloadCsv as DownloadCsvRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Download\DownloadCsvManager;
use App\Models\Activity\Activity;

/**
 * Class DownloadCsvManagerTest
 */
class DownloadCsvManagerTest extends AidStreamTestCase
{
    public function setUp()
    {
        $this->activity = m::mock(Activity::class);
        $this->collection = m::mock(Collection::class);
        $this->version = m::mock(Version::class);
        $this->downloadCsv = m::mock(DownloadCsv::class);
        $this->iatiActivity = m::mock(IatiActivity::class);
        $this->completeCsvDataFormatter = m::mock(CompleteCsvDataFormatter::class);
        $this->simpleCsvDataFormatter = m::mock(SimpleCsvDataFormatter::class);
        $this->transactionCsvDataFormatter = m::mock(TransactionCsvDataFormatter::class);
        $this->downloadCsvRepository = m::mock(DownloadCsvRepository::class);

        $this->version->shouldReceive('getActivityElement')->once()->andReturn($this->iatiActivity);

        $this->iatiActivity->shouldReceive('getDownloadCsv')->times(4)->andReturn($this->downloadCsv);
        $this->downloadCsv->shouldReceive('getRepository')->once()->andReturn($this->downloadCsvRepository);
        $this->downloadCsv->shouldReceive('getSimpleCsvDataFormatter')->once()->andReturn($this->simpleCsvDataFormatter);
        $this->downloadCsv->shouldReceive('getCompleteCsvDataFormatter')->once()->andReturn($this->completeCsvDataFormatter);
        $this->downloadCsv->shouldReceive('getTransactionCsvDataFormatter')->once()->andReturn($this->transactionCsvDataFormatter);

        $this->downloadCsvManager = new DownloadCsvManager($this->version);
    }

    /** @test */
    public function itShouldReturnAllActivities()
    {
        $this->downloadCsvRepository->shouldReceive('getAllActivities')->once()->andReturn($this->collection);
        $this->assertInstanceOf(Collection::class, $this->downloadCsvManager->getAllActivities());
    }

    /** @test */
    public function itShouldReturnActivityTransactions()
    {
        $this->downloadCsvRepository->shouldReceive('getActivityTransactions')->with(1)->once()->andReturn($this->collection);
        $this->assertInstanceOf(Collection::class, $this->downloadCsvManager->getActivityTransactions(1));
    }

    /** @test */
    public function itShouldReturnSimpleCsvData()
    {
        $this->downloadCsvRepository->shouldReceive('simpleCsvData')->with(1)->once()->andReturn($this->collection);
        $this->simpleCsvDataFormatter->shouldReceive('format')->with($this->collection);

        $this->assertEquals(null, $this->downloadCsvManager->simpleCsvData(1));
    }

    /** @test */
    public function itShouldReturnCompleteCsvData()
    {
        $this->downloadCsvRepository->shouldReceive('completeCsvData')->with(1)->once()->andReturn($this->collection);
        $this->completeCsvDataFormatter->shouldReceive('format')->with($this->collection);

        $this->assertEquals(null, $this->downloadCsvManager->completeCsvData(1));
    }

    /** @test */
    public function itShouldReturnTransactionCsvData()
    {
        $this->downloadCsvRepository->shouldReceive('simpleCsvData')->with(1)->once()->andReturn($this->collection);
        $this->transactionCsvDataFormatter->shouldReceive('format')->with($this->collection);

        $this->assertEquals(null, $this->downloadCsvManager->transactionCsvData(1));
    }


    public function tearDown()
    {
        parent::teardown();
        m::close();
    }
}
