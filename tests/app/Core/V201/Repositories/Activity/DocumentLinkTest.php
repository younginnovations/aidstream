<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Activity\DocumentLink;
use App\Models\Activity\ActivityDocumentLink;
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
    protected $documentLinkModel;
    protected $documentLink;

    public function setUp()
    {
        parent::setUp();
        $this->documentLinkModel = m::mock(ActivityDocumentLink::class);
        $this->documentLink      = new DocumentLink($this->documentLinkModel);
    }

    public function testItShouldUpdateActivityDocumentLink()
    {
        $this->documentLinkModel->shouldReceive('setAttribute')->once()->with(
            'document_link',
            'testDocumentLink'
        );
        $this->documentLinkModel->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue(
            $this->documentLink->update(['testDocumentLink'], $this->documentLinkModel)
        );

    }

    public function testItShouldReturnDocumentLinkWithSpecificIdAndActivityId()
    {
        $this->documentLinkModel->shouldReceive('firstOrNew')->once()->with(['id' => 1, 'activity_id' => 2])->andReturn($this->documentLinkModel);
        $this->assertInstanceOf('App\Models\Activity\ActivityDocumentLink', $this->documentLink->getDocumentLink(1, 2));
    }

    public function testItShouldReturnDocumentLinksWithSpecificActivityId()
    {
        $this->documentLinkModel->shouldReceive('where->get')->once()->andReturn([]);
        $this->assertEquals([], $this->documentLink->getDocumentLinks(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
