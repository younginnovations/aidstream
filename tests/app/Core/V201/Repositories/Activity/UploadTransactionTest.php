<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\Elements\CsvReader;
use App\Core\V201\Repositories\Activity\Transaction as TransactionRepo;
use App\Core\V201\Repositories\Activity\UploadTransaction;
use App\Models\Activity\Activity;
use App\Models\Activity\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Test\AidStreamTestCase;
use Mockery as m;

class UploadTransactionTest extends AidStreamTestCase
{
    protected $transactionModel;
    protected $csvReader;
    protected $transactionRepo;
    protected $uploadTransactionRepo;

    public function setUp()
    {
        parent::setUp();
        $this->transactionModel      = m::mock(Transaction::class);
        $this->csvReader             = m::mock(CsvReader::class);
        $this->transactionRepo       = m::mock(TransactionRepo::class);
        $this->uploadTransactionRepo = new UploadTransaction($this->transactionModel, $this->transactionRepo, $this->csvReader);
    }

    public function testItShouldUploadTransaction()
    {
        $activityModel = m::mock(Activity::class);
        $this->transactionModel->shouldReceive('newInstance')->once()->with(['transaction' => []])->andReturnSelf();
        $activityModel->shouldReceive('transactions->save')->once()->with($this->transactionModel)->andReturn(true);
        $this->uploadTransactionRepo->upload([], $activityModel);
    }

    public function testItShouldUpdateTransactionByUploadingTransaction()
    {
        $this->transactionRepo->shouldReceive('getTransaction')->with(1)->andReturn($this->transactionModel);
        $this->transactionModel->shouldReceive('setAttribute')->with('transaction', []);
        $this->transactionModel->shouldReceive('save')->andReturn(true);
        $this->uploadTransactionRepo->update([], 1);
    }

    public function testItShouldFormatFormExcelRow()
    {
        $this->csvReader->shouldReceive('getTransactionHeaders')->with('Detailed')->andReturn([]);

        $transactionRow = [
            'transaction_ref'                  => '',
            'transactiontype_code'             => [],
            'transactiondate_iso_date'         => '2016-01-01',
            'transactionvalue_value_date'      => '2016-01-01',
            'transactionvalue_text'            => [],
            'transactionvalue_currency'        => '',
            'description_text'                 => [],
            'providerorg_ref'                  => [],
            'providerorg_provider_activity_id' => [],
            'providerorg_narrative_text'       => [],
            'receiverorg_ref'                  => [],
            'receiverorg_receiver_activity_id' => [],
            'receiverorg_narrative_text'       => [],
            'disbursementchannel_code'         => [],
            'sector_code'                      => [],
            'sector_vocabulary'                => [],
            'recipientcountry_code'            => [],
            'recipientregion_code'             => [],
            'recipientregion_vocabulary'       => [],
            'flowtype_code'                    => [],
            'financetype_code'                 => [],
            'aidtype_code'                     => [],
            'tiedstatus_code'                  => []
        ];
        $this->assertTrue(is_array($this->uploadTransactionRepo->formatFromExcelRow($transactionRow)));
    }

    public function testItShouldGetTransactionReferencesWithCertainActivityId()
    {
        $collection    = m::mock(Collection::class);
        $arrayIterator = new \ArrayIterator([$this->transactionModel]);

        $this->transactionModel->shouldReceive('where->get')->andReturn($collection);
        $collection->shouldReceive('getIterator')->andReturn($arrayIterator);
        $this->transactionModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $this->transactionModel->shouldReceive('getAttribute')->once()->with('transaction')->andReturn(['reference' => 'r']);

        $this->assertTrue(is_array($this->uploadTransactionRepo->getTransactionReferences(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
