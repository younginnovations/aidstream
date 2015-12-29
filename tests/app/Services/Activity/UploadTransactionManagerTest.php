<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\Transaction;
use App\Core\V201\Repositories\Activity\UploadTransaction;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Services\Activity\UploadTransactionManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Collections\RowCollection;
use Psr\Log\LoggerInterface as Logger;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Test\AidStreamTestCase;
use Exception;
use Mockery as m;

class UploadTransactionManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $dbLogger;
    protected $logger;
    protected $transactionRepo;
    protected $uploadTransactionRepo;
    protected $uploadTransactionManager;

    public function SetUp()
    {
        parent::setUp();
        $this->version               = m::mock(Version::class);
        $this->auth                  = m::mock(Guard::class);
        $this->dbLogger              = m::mock(DbLogger::class);
        $this->logger                = m::mock(Logger::class);
        $this->transactionRepo       = m::mock(Transaction::class);
        $this->uploadTransactionRepo = m::mock(UploadTransaction::class);
        $this->version->shouldReceive('getActivityElement->getTransaction->getRepository')->andReturn($this->transactionRepo);
        $this->version->shouldReceive('getActivityElement->getUploadTransaction->getRepository')->andReturn($this->uploadTransactionRepo);
        $this->uploadTransactionManager = new UploadTransactionManager($this->version, $this->auth, $this->dbLogger, $this->logger);
    }

    public function testItShouldSaveUploadedTransaction()
    {
        $transactionCsv = m::mock(UploadedFile::class);
        $transaction    = m::mock(CellCollection::class);
        $collection     = m::mock(RowCollection::class);
        $excel          = m::mock('Maatwebsite\Excel\Excel');
        $this->version->shouldReceive('getExcel')->once()->andReturn($excel);
        $excel->shouldReceive('load->get')->once()->andReturn($collection);
        $arrayIterator = new \ArrayIterator([$transaction]);

        $collection->shouldReceive('getIterator')->andReturn($arrayIterator);
        $this->uploadTransactionRepo->shouldReceive('formatFromExcelRow')->with($transaction)->andReturn(['reference' => 'r']);
        $activity = m::mock(Activity::class);
        $activity->shouldReceive('getAttribute')->with('id')->andReturn(1);

        $this->uploadTransactionRepo->shouldReceive('getTransactionReferences')->with(1)->andReturn([]);
        $this->uploadTransactionRepo->shouldReceive('upload')->with(['reference' => 'r'], $activity);

        $this->logger->shouldReceive('info')->once()->with('Transactions Uploaded for activity with id :1');
        $this->dbLogger->shouldReceive('activity')->once()->with('activity.transaction_uploaded', ['activity_id' => 1]);
        $this->assertTrue($this->uploadTransactionManager->save($transactionCsv, $activity));
    }

    public function testItShouldUpdateTransactionByUploadingTransaction()
    {
        $transactionCsv = m::mock(UploadedFile::class);
        $transaction    = m::mock(CellCollection::class);
        $collection     = m::mock(RowCollection::class);
        $excel          = m::mock('Maatwebsite\Excel\Excel');
        $this->version->shouldReceive('getExcel')->once()->andReturn($excel);
        $excel->shouldReceive('load->get')->once()->andReturn($collection);
        $arrayIterator = new \ArrayIterator([$transaction]);

        $collection->shouldReceive('getIterator')->andReturn($arrayIterator);
        $this->uploadTransactionRepo->shouldReceive('formatFromExcelRow')->with($transaction)->andReturn(['reference' => 'r']);
        $activity = m::mock(Activity::class);
        $activity->shouldReceive('getAttribute')->with('id')->andReturn(1);

        $this->uploadTransactionRepo->shouldReceive('getTransactionReferences')->with(1)->andReturn(['r' => '']);
        $this->uploadTransactionRepo->shouldReceive('update')->with(['reference' => 'r'], '');

        $this->logger->shouldReceive('info')->once()->with('Transactions Uploaded for activity with id :1');
        $this->dbLogger->shouldReceive('activity')->once()->with('activity.transaction_uploaded', ['activity_id' => 1]);
        $this->assertTrue($this->uploadTransactionManager->save($transactionCsv, $activity));
    }

    public function testItShouldReturnExceptionIfErrorOccursInTransactionUpload()
    {
        $transactionCsv = m::mock(UploadedFile::class);
        $transaction    = m::mock(CellCollection::class);
        $collection     = m::mock(RowCollection::class);
        $excel          = m::mock('Maatwebsite\Excel\Excel');
        $this->version->shouldReceive('getExcel')->once()->andReturn($excel);
        $excel->shouldReceive('load->get')->once()->andReturn($collection);
        $arrayIterator = new \ArrayIterator([$transaction]);

        $collection->shouldReceive('getIterator')->andReturn($arrayIterator);
        $this->uploadTransactionRepo->shouldReceive('formatFromExcelRow')->with($transaction)->andReturn(['reference' => 'r']);
        $activity = m::mock(Activity::class);
        $activity->shouldReceive('getAttribute')->with('id')->andReturn(1);

        $this->uploadTransactionRepo->shouldReceive('getTransactionReferences')->with(1)->andReturn(['r' => '']);
        $exception = m::mock(Exception::class);
        $this->uploadTransactionRepo->shouldReceive('update')->andThrow($exception);
        $this->logger->shouldReceive('error');
        $this->assertFalse($this->uploadTransactionManager->save($transactionCsv, $activity));
    }
}
