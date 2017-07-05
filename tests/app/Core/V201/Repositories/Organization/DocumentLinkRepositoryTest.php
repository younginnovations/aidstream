<?php namespace Test\app\Core\V201\Repositories\Organization;

use App\Core\V201\Repositories\Organization\DocumentLinkRepository;
use Mockery as m;
use Test\AidStreamTestCase;

class DocumentLinkRepositoryTest extends AidStreamTestCase
{

    protected $documentLinkRepository;
    protected $organizationData;

    public function setUp()
    {
        parent::setUp();
        $this->organizationData = m::mock('App\Models\Organization\OrganizationData');
        $this->documentLinkRepository = new DocumentLinkRepository($this->organizationData);
    }

    /**
     * @test
     */
    public function testItShouldUpdateOrganizationDocumentLinkData()
    {
        $this->organizationData->shouldReceive('setAttribute')->once()->with('document_link', 'a');
        $this->organizationData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue($this->documentLinkRepository->update(['document_link' => 'a'], $this->organizationData));
    }

    /**
     * @test
     */
    public function testItShouldGetOrganizationDataWithSpecificId()
    {
        $this->organizationData->shouldReceive('where->first')->once()->andReturn($this->organizationData);
        $this->assertInstanceOf('App\Models\Organization\OrganizationData', $this->documentLinkRepository->getOrganizationData(1));
    }

    /**
     * @test
     */
    public function testItShouldReturnGetOrganizationDocumentLinkDataWithSpecificId()
    {
        $this->organizationData->shouldReceive('where->first')->once()->andReturn($this->organizationData);
        $this->organizationData->shouldReceive('getAttribute')->once()->with('document_link')->andReturn([]);
        $this->assertTrue(is_array($this->documentLinkRepository->getDocumentLinkData(1)));
    }

    /**
     * @test
     */
    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
