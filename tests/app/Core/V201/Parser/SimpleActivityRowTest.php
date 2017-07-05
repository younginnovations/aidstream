<?php namespace tests\app\Core\V201\Parser;

use App\Core\V201\Parser\ActivityDataParser;
use App\Core\V201\Parser\SimpleActivityRow;
use Test\AidStreamTestCase;
use Mockery as m;
use App\Core\V201\Parser\ActivityCsvFieldChecker;

class SimpleActivityRowTest extends AidStreamTestCase
{
    protected $checker;
    protected $parser;
    protected $simpleActivityRow;

    public function setUp()
    {
        parent::setUp();
        $this->checker = m::mock(ActivityCsvFieldChecker::class);
        $this->parser  = m::mock(ActivityDataParser::class);

        $this->simpleActivityRow = new SimpleActivityRow($this->checker, $this->parser);
    }

    public function testItShouldReturnVerifiedRow()
    {
        $row = ['data'];
        $this->checker->shouldReceive('init')->once()->with($row)->andReturn($this->checker);
        $this->checker->shouldReceive('checkIdentifier');
        $this->checker->shouldReceive('checkTitle');
        $this->checker->shouldReceive('checkDescription');
        $this->checker->shouldReceive('checkStatus');
        $this->checker->shouldReceive('checkDate');
        $this->checker->shouldReceive('checkParticipatingOrg');
        $this->checker->shouldReceive('checkRecipientCountryOrRegion');
        $this->checker->shouldReceive('checkSector');
        $this->checker->shouldReceive('getErrors')->andReturn('errors');

        $this->assertEquals(['data' => ['data'], 'errors' => 'errors'], $this->simpleActivityRow->getVerifiedRow($row));
    }

    public function testItShouldSaveActivity()
    {
        $activity = ['activityData'];
        $this->parser->shouldReceive('init')->with($activity)->andReturn($this->parser);
        $this->parser->shouldReceive('setIdentifier');
        $this->parser->shouldReceive('setTitle');
        $this->parser->shouldReceive('setDescription');
        $this->parser->shouldReceive('setStatus');
        $this->parser->shouldReceive('setDate');
        $this->parser->shouldReceive('setParticipatingOrg');
        $this->parser->shouldReceive('setRecipientCountry');
        $this->parser->shouldReceive('setRecipientRegion');
        $this->parser->shouldReceive('setSector');
        $this->parser->shouldReceive('save')->andReturn(true);

        $this->assertTrue($this->simpleActivityRow->save($activity));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
