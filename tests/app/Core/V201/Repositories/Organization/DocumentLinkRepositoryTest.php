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

    public function testItShouldUpdateOrganizationDocumentLinkData()
    {
//        $organizationModel = m::mock('App\Models\Organization\Organization');
        $this->organizationData->shouldReceive('setAttribute')->once()->with('document_link', 'a');
        $this->organizationData->shouldReceive('save')->once()->andReturn(true);
        $this->assertTrue($this->documentLinkRepository->update(['documentLink' => 'a'], $this->organizationData));
    }

    public function testItShouldGetOrganizationDataWithSpecificId()
    {
        $this->organizationData->shouldReceive('where->first')->once()->andReturn($this->organizationData);
        $this->assertInstanceOf('App\Models\Organization\OrganizationData', $this->documentLinkRepository->getOrganizationData(1));
    }

    public function testItShouldReturnGetOrganizationDocumentLinkDataWithSpecificId()
    {
        $this->organizationData->shouldReceive('where->first')->once()->andReturn($this->organizationData);
        $this->organizationData->shouldReceive('getAttribute')->once()->with('document_link')->andReturn([]);
        $this->assertTrue(is_array($this->documentLinkRepository->getDocumentLinkData(1)));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

}
