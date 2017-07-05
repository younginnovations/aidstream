<?php namespace tests\app\Core\V201\Parser;

use App\Core\V201\Parser\SimpleActivity;
use Maatwebsite\Excel\Readers\LaravelExcelReader;
use Test\AidStreamTestCase;
use Mockery as m;
use App\Core\V201\Parser\SimpleActivityRow;

class SimpleActivityTest extends AidStreamTestCase
{

    protected $simpleActivityRow;
    protected $simpleActivity;
    protected $excelReader;

    public function setUp()
    {
        parent::setUp();
        $this->simpleActivityRow = m::mock(SimpleActivityRow::class);
        $this->excelReader       = m::mock(LaravelExcelReader::class);

        $this->simpleActivity = new SimpleActivity($this->simpleActivityRow);
    }

    public function testItShouldReturnVerifiedActivitiesWithErrors()
    {
        $csvData = [1, 2];
        $this->excelReader->shouldReceive('toArray')->andReturn($csvData);
        $this->simpleActivityRow->shouldReceive('getVerifiedRow')->twice()->andReturn('Verified Row');

        $activities = ['Verified Row', 'Verified Row', 'duplicate_identifiers' => [null]];
        $this->assertEquals($activities, $this->simpleActivity->getVerifiedActivities($this->excelReader));

    }

    public function testItShouldSaveActivities()
    {
        $csvData = ['[1]', '[2]'];

        $this->simpleActivityRow->shouldReceive('save')->twice()->andReturn(true);

        $this->assertEquals([true, true], $this->simpleActivity->save($csvData));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
