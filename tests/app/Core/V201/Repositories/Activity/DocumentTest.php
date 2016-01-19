<?php namespace Test\app\Core\V201\Repositories\Activity;

use App\Core\V201\Repositories\Document as DocumentRepository;
use App\Models\Document;
use Mockery as m;
use Test\AidStreamTestCase;
use Illuminate\Support\Collection;

/**
 * Class DocumentTest
 * @package Test\app\Core\V201\Repositories\Activity
 */
class DocumentTest extends AidStreamTestCase
{
    protected $document;
    protected $documentRepo;

    public function setUp()
    {
        parent::setUp();
        $this->document     = m::mock(Document::class);
        $this->documentRepo = new DocumentRepository($this->document);
    }

    public function testItShouldReturnDocumentsOfTheOrganization()
    {
        $this->document->shouldReceive('where')->once()->with('org_id', 1)->andReturnSelf();
        $this->document->shouldReceive('where')->once()->with('filename', '<>', 'NULL')->andReturnSelf();
        $this->document->shouldReceive('get')->once()->andReturn(m::mock(Collection::class));
        $this->assertInstanceOf('Illuminate\Support\Collection', $this->documentRepo->getDocuments(1));
    }

    public function testItShouldReturnSpecificDocument()
    {
        $this->document->shouldReceive('firstOrNew')->once()->with(['url' => 'url', 'org_id' => 1, 'filename' => 'filename'])->andReturn($this->document);
        $this->assertInstanceOf('App\Models\Document', $this->documentRepo->getDocument(1, 'url', 'filename'));
    }

    public function testItShouldStoreDocument()
    {
        $this->document->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue($this->documentRepo->store($this->document));

    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
