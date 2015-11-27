<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\Result;
use App\Models\Activity\Activity;
use App\Models\Activity\ActivityResult;
use Illuminate\Database\Eloquent\Collection;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class ResultTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class ResultTest extends AidStreamTestCase
{
    protected $activityData;
    protected $activityResult;
    protected $result;

    public function setUp()
    {
        parent::setUp();
        $this->activityData   = m::mock(Activity::class);
        $this->activityResult = m::mock(ActivityResult::class);
        $this->result         = new Result($this->activityData, $this->activityResult);
    }

    public function testItShouldUpdateActivityResult()
    {
        $this->activityResult->shouldReceive('setAttribute')->once()->with(
            'result',
            'testResult'
        );
        $this->activityResult->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->result->update(['result' => ['testResult']], $this->activityResult)
        );

    }

    public function testItShouldReturnResults()
    {
        $collection = m::mock(Collection::class);
        $this->activityResult->shouldReceive('where->get')->andReturn($collection);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->result->getResults(1));
    }

    public function testItShouldReturnResultData()
    {
        $this->activityResult->shouldReceive('firstOrNew')->once()->with(['id' => 1, 'activity_id' => 1])->andReturn($this->activityResult);
        $this->assertInstanceOf('App\Models\Activity\ActivityResult', $this->result->getResult(1, 1));
    }

    public function testItShouldDeleteActivityResult()
    {
        $this->activityResult->shouldReceive('delete')->andReturn(true);
        $this->assertTrue($this->result->deleteResult($this->activityResult));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
