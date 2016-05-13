<?php namespace tests\app\Services\Activity;

use App\Core\Version;
use App\Models\Organization\Organization;
use App\Services\Activity\ImportActivity;
use App\Services\Organization\OrganizationManager;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Readers\LaravelExcelReader;
use Test\AidStreamTestCase;
use Mockery as m;
use App\Core\V201\Parser\SimpleActivity;
use App\Core\V201\Parser\SimpleActivityDemo;

class ImportActivityTest extends AidStreamTestCase
{

    protected $simpleActivityParser;
    protected $simpleActivityDemoParser;
    protected $version;
    protected $logger;
    protected $importActivity;
    protected $excelReader;
    protected $importActivityMock;
    protected $database;
    protected $orgManager;

    public function setUp()
    {
        parent::setUp();
        $this->simpleActivityParser     = m::mock(SimpleActivity::class);
        $this->simpleActivityDemoParser = m::mock(SimpleActivityDemo::class);
        $this->version                  = m::mock(Version::class);
        $this->logger                   = m::mock(Log::class);
        $this->excelReader              = m::mock(LaravelExcelReader::class);
        $this->importActivityMock       = m::mock(ImportActivity::class);
        $this->database                 = m::mock(DatabaseManager::class);
        $this->orgManager               = m::mock(OrganizationManager::class);

        $this->version->shouldReceive('getActivityElement->getSimpleActivityParser')->andReturn($this->simpleActivityParser);
        $this->version->shouldReceive('getActivityElement->getSimpleActivityDemoParser')->andReturn($this->simpleActivityDemoParser);

        $this->importActivity = new ImportActivity($this->version, $this->logger, $this->orgManager);
    }

    public function testItShouldReturnActivitiesFromCsvFile()
    {
        $csvFile    = '';
        $firstData  = ['firstData'];
        $activities = [$firstData];

        $this->version->shouldReceive('getExcel->load')->once()->with($csvFile)->andReturn($this->excelReader);
        $this->excelReader->shouldReceive('toArray')->once()->andReturn([$firstData]);
        $this->excelReader->shouldReceive('get->count')->once()->andReturn(1);
        $this->simpleActivityParser->shouldReceive('getTemplate')->once()->with($firstData)->andReturn($this->simpleActivityParser);
        $this->simpleActivityParser->shouldReceive('getVerifiedActivities')->once()->with($this->excelReader)->andReturn($activities);

        $this->assertEquals($activities, $this->importActivity->getActivities($csvFile));
    }

    public function testItShouldImportSelectedActivities()
    {
        $firstData  = '["firstData"]';
        $activities = [$firstData];
        session()->put('org_id', 60);
        $orgModel = m::mock(Organization::class);

        $this->database->shouldReceive('beginTransaction');
        $this->orgManager->shouldReceive('getOrganization')->once()->with(session('org_id'))->andReturn($orgModel);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('Org Name');
        $orgModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(session('org_id'));
        $this->simpleActivityParser->shouldReceive('getTemplate')->once()->with(json_decode($firstData, true))->andReturn($this->simpleActivityParser);
        $this->simpleActivityParser->shouldReceive('save')->once()->with($activities)->andReturn([]);
        $this->database->shouldReceive('commit');
        $this->logger->shouldReceive('activity')->once()->with("activity.activity_uploaded", ['organization' => 'Org Name', 'organization_id' => session('org_id')]);

        $this->assertTrue($this->importActivity->importActivities($activities));
    }

}
