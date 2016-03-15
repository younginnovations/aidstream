<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\Transaction;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Services\Activity\TransactionManager;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Psr\Log\LoggerInterface as Logger;
use Test\AidStreamTestCase;
use Mockery as m;

class TransactionManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $dbLogger;
    protected $logger;
    protected $transactionRepo;
    protected $transactionManager;

    public function setUp()
    {
        parent::setUp();
        $this->version         = m::mock(Version::class);
        $this->auth            = m::mock(Guard::class);
        $this->dbLogger        = m::mock(DbLogger::class);
        $this->logger          = m::mock(Logger::class);
        $this->transactionRepo = m::mock(Transaction::class);
        $this->version->shouldReceive('getActivityElement->getTransaction->getRepository')->andReturn($this->transactionRepo);
        $this->transactionManager = new TransactionManager($this->version, $this->auth, $this->dbLogger, $this->logger);
    }

    public function testItShouldUpdateTransactionDetails()
    {
        $this->transactionRepo->shouldReceive('update')->once()->with([], 1);
        $this->logger->shouldReceive('info')->with('Activity Transaction Updated');
        $activity = m::mock(Activity::class);
        $activity->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $this->dbLogger->shouldReceive('activity')->with('activity.transaction_updated', ['activity_id' => 1, 'transaction_id' => 1]);
        $this->assertTrue($this->transactionManager->save([], $activity, 1));
    }

    public function testItShouldCreateTransactionDetails()
    {
        $activity = m::mock(Activity::class);
        $this->transactionRepo->shouldReceive('create')->once()->with([], $activity);
        $this->logger->shouldReceive('info')->with('Activity Transaction added');
        $activity->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $this->dbLogger->shouldReceive('activity')->with('activity.transaction_added', ['activity_id' => 1]);
        $this->assertTrue($this->transactionManager->save([], $activity));
    }

    public function testItShouldThrowExceptionInCaseProblemOccursWhileUpdatingTransaction()
    {
        $activity  = m::mock(Activity::class);
        $exception = m::mock(Exception::class);
        $this->transactionRepo->shouldReceive('update')->with([], $activity)->andThrow($exception);
        $this->logger->shouldReceive('error');
        $this->assertFalse($this->transactionManager->save([], $activity, 1));
    }
}
