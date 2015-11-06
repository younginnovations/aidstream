<?php namespace Test\app\Core\V201\Repositories\Organization;

use App\Services\Organization\DocumentLinkManager;
use Mockery as m;
use Test\AidStreamTestCase;

class DocumentLinkManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $logger;
    protected $auth;
    protected $documentLinkRepository;
    protected $docLinkManager;

    public function setUp()
    {
        parent::setUp();
        $this->version = m::mock('App\Core\Version');
        $this->documentLinkRepository = m::mock('App\Core\V201\Repositories\Organization\DocumentLinkRepository');
        $this->version->shouldReceive('getOrganizationElement->getDocumentLink->getRepository')->andReturn($this->documentLinkRepository);
        $this->logger = m::mock('Illuminate\Contracts\Logging\Log');
        $this->auth = m::mock('Illuminate\Auth\Guard');
        $this->docLinkManager = new DocumentLinkManager($this->version, $this->logger, $this->auth);
    }

    public function testItShouldUpdateDocumentLinkData()
    {
        $organizationModel = m::mock('App\Models\Organization\Organization');
        $organizationModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('organizationName');
        $user = m::mock('App\User');
        $user->shouldReceive('getAttribute')->once()->with('organization')->andReturn($organizationModel);
        $this->auth->shouldReceive('user')->once()->andReturn($user);
        $organizationDataModel = m::mock('App\Models\Organization\OrganizationData');
        $organizationDataModel->shouldReceive('getAttribute')->once()->with('document_link')->andReturn('documentLink');
        $this->documentLinkRepository->shouldReceive('update')->once()->with([], $organizationDataModel)->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with("Document Link Updated", ['for' => 'documentLink']);
        $this->logger->shouldReceive('activity')->once()->with('organization.document_link_updated', ['name'=>'organizationName']);
        $this->assertTrue($this->docLinkManager->update([], $organizationDataModel));
    }

    public function testItShouldGetAllOrganizationDataWithCertainId()
    {
        $this->documentLinkRepository->shouldReceive('getOrganizationData')->with(1)->andReturn(m::mock('App\Models\Organization\OrganizationData'));
        $this->assertInstanceOf('App\Models\Organization\OrganizationData', $this->docLinkManager->getOrganizationData(1));
    }

    public function testItShouldGetOrganizationDocumentLinkDataWithCertainId()
    {
        $this->documentLinkRepository->shouldReceive('getDocumentLinkData')->with(1)->andReturn(m::mock('App\Models\Organization\OrganizationData'));
        $this->assertInstanceOf('App\Models\Organization\OrganizationData', $this->docLinkManager->getDocumentLinkData(1) );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
