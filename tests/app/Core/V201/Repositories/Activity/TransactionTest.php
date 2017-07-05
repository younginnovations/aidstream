<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\Transaction as TransactionRepo;
use App\Models\Activity\Activity;
use App\Models\Activity\Transaction;
use Test\AidStreamTestCase;
use Mockery as m;

class TransactionTest extends AidStreamTestCase
{
    protected $transactionModel;
    protected $transactionRepo;

    public function setUp()
    {
        parent::setUp();
        $this->transactionModel = m::mock(Transaction::class);
        $this->transactionRepo  = new TransactionRepo($this->transactionModel);
    }

    public function testItShouldCreateTransactionCorrespondingToCertainActivityId()
    {
        $transaction = ['transaction' => [['sector' => [['sector_code' => 11110, 'sector_vocabulary' => 1]]]]];
        $activityModel = m::mock(Activity::class);
        $this->transactionModel->shouldReceive('newInstance')->once()->with(['transaction' => $transaction['transaction'][0]])->andReturnSelf();
        $activityModel->shouldReceive('transactions->save')->once()->with($this->transactionModel)->andReturn(true);
        $this->assertNull($this->transactionRepo->create($transaction, $activityModel));
    }

    public function testItShouldUpdateTransaction()
    {
        $transaction = ['transaction' => [['sector' => [['sector_code' => 11110, 'sector_vocabulary' => 1]]]]];
        $this->transactionModel->shouldReceive('findOrFail')->with(1)->andReturn($this->transactionModel);
        $this->transactionModel->shouldReceive('setAttribute')->with('transaction', $transaction['transaction'][0]);
        $this->transactionModel->shouldReceive('save')->andReturn(true);
        $this->assertNull($this->transactionRepo->update($transaction, 1));
    }

    public function testItShouldGetTheTransactionsWithCertainId()
    {
        $this->transactionModel->shouldReceive('findOrFail')->with(1)->andReturn($this->transactionModel);
        $this->assertInstanceOf('App\Models\Activity\Transaction', $this->transactionRepo->getTransaction(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
