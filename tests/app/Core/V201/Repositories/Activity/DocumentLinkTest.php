<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\DocumentLink;
use App\Models\Activity\Activity;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class DocumentLinkTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class DocumentLinkTest extends AidStreamTestCase
{
    /**
     * @var
     */
    protected $activityData;
    protected $documentLink;

    public function setUp()
    {
        parent::setUp();
        $this->activityData      = m::mock(Activity::class);
        $this->documentLink = new DocumentLink($this->activityData);
    }

    public function testItShouldUpdateActivityDocumentLink()
    {
        $this->activityData->shouldReceive('setAttribute')->once()->with(
            'document_link',
            'testDocumentLink'
        );
        $this->activityData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->documentLink->update(['document_link' => 'testDocumentLink'], $this->activityData)
        );

    }

    public function testItShouldReturnDocumentLinkData()
    {
        $this->activityData->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive(
            'getAttribute'
        )->once()->with('document_link')->andReturn([]);
        $this->assertTrue(is_array($this->documentLink->getDocumentLinkData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
