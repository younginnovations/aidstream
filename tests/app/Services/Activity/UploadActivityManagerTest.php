<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\UploadActivity;
use App\Core\Version;
use App\Models\Organization\Organization;
use App\Services\Activity\UploadActivityManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Illuminate\Database\DatabaseManager;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Collections\RowCollection;
use Psr\Log\LoggerInterface as Logger;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Test\AidStreamTestCase;
use Exception;
use Mockery as m;

class UploadActivityManagerTest extends AidStreamTestCase
{

    protected $version;
    protected $auth;
    protected $dbLogger;
    protected $logger;
    protected $uploadActivityRepo;
    protected $database;
    protected $uploadActivityManager;

    public function SetUp()
    {
        parent::setUp();
        $this->version            = m::mock(Version::class);
        $this->auth               = m::mock(Guard::class);
        $this->dbLogger           = m::mock(DbLogger::class);
        $this->logger             = m::mock(Logger::class);
        $this->uploadActivityRepo = m::mock(UploadActivity::class);
        $this->database           = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getActivityElement->getUploadActivity->getRepository')->andReturn($this->uploadActivityRepo);
        $this->uploadActivityManager = new UploadActivityManager($this->version, $this->auth, $this->database, $this->dbLogger, $this->logger);
    }

    public function testItShouldSaveUploadedActivity()
    {
        $activityCsv = m::mock(UploadedFile::class);
        $activity    = m::mock(CellCollection::class);
        $collection  = m::mock(RowCollection::class);
        $excel       = m::mock('Maatwebsite\Excel\Excel');
        $this->version->shouldReceive('getExcel')->once()->andReturn($excel);
        $excel->shouldReceive('load->get')->once()->andReturn($collection);
        $arrayIterator = new \ArrayIterator([$activity]);

        $collection->shouldReceive('getIterator')->andReturn($arrayIterator);
        $organization = m::mock(Organization::class);
        $organization->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $this->uploadActivityRepo->shouldReceive('formatFromExcelRow')->with($activity, 1)->andReturn(['identifier' => ['activity_identifier' => 'i']]);

        $this->uploadActivityRepo->shouldReceive('getIdentifiers')->with(1)->andReturn([]);
        $this->database->shouldReceive('beginTransaction');

        $this->uploadActivityRepo->shouldReceive('upload')->with(['identifier' => ['activity_identifier' => 'i']], $organization);
        $this->database->shouldReceive('commit');
        $this->logger->shouldReceive('info')->once()->with('Activities Uploaded for organization with id:1');
        $this->dbLogger->shouldReceive('activity')->once()->with('activity.activity_uploaded', ['organization_id' => 1]);
        $this->assertTrue($this->uploadActivityManager->save($activityCsv, $organization));
    }

    public function testItShouldSaveUpdateActivity()
    {
        $this->uploadActivityRepo->shouldReceive('update')->with([], 1);
    }

    public function testItShouldReturnExceptionIfErrorOccursInActivityUpload()
    {
        $activityCsv = m::mock(UploadedFile::class);
        $activity    = m::mock(CellCollection::class);
        $collection  = m::mock(RowCollection::class);
        $excel       = m::mock('Maatwebsite\Excel\Excel');
        $this->version->shouldReceive('getExcel')->once()->andReturn($excel);
        $excel->shouldReceive('load->get')->once()->andReturn($collection);
        $arrayIterator = new \ArrayIterator([$activity]);

        $collection->shouldReceive('getIterator')->andReturn($arrayIterator);
        $organization = m::mock(Organization::class);
        $organization->shouldReceive('getAttribute')->with('id')->andReturn(1);
        $this->uploadActivityRepo->shouldReceive('formatFromExcelRow')->with($activity, 1)->andReturn(['identifier' => ['activity_identifier' => 'i']]);

        $this->uploadActivityRepo->shouldReceive('getIdentifiers')->with(1)->andReturn([]);
        $exception = m::mock(Exception::class);
        $this->uploadActivityRepo->shouldReceive('update')->andThrow($exception);
        $this->database->shouldReceive('rollback');
        $this->logger->shouldReceive('error');
        $this->assertFalse($this->uploadActivityManager->save($activityCsv, $organization));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
