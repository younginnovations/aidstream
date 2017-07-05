<?php namespace Test\app\Services;

use App\Core\Version;
use App\Models\Document;
use App\Models\Organization\Organization;
use App\Services\DocumentManager;
use App\Core\V201\Repositories\Document as DocumentRepository;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as DbLogger;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface as Logger;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class DocumentManagerTest
 * @package Test\app\Services\Activity
 */
class DocumentManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $dbLogger;
    protected $logger;
    protected $documentRepo;
    protected $documentManager;
    protected $database;
    protected $document;

    /**
     * @test
     */
    public function SetUp()
    {
        parent::setUp();
        $this->version      = m::mock(Version::class);
        $this->auth         = m::mock(Guard::class);
        $this->dbLogger     = m::mock(DbLogger::class);
        $this->logger       = m::mock(Logger::class);
        $this->document     = m::mock(Document::class);
        $this->documentRepo = m::mock(DocumentRepository::class);
        $this->database     = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getSettingsElement->getDocumentRepository')->andReturn(
            $this->documentRepo
        );
        $this->documentManager = new DocumentManager(
            $this->version,
            $this->auth,
            $this->database,
            $this->dbLogger,
            $this->logger
        );
    }

    /**
     * @test
     */
    public function testItShouldReturnDocumentsOfTheOrganization()
    {
        $this->documentRepo->shouldReceive('getDocuments')->once()->with(1)->andReturn(m::mock(Collection::class));
        $this->assertInstanceOf('Illuminate\Support\Collection', $this->documentManager->getDocuments(1));
    }

    /**
     * @test
     */
    public function testItShouldReturnSpecificDocument()
    {
        $this->documentRepo->shouldReceive('getDocument')->once()->with(1, 'url', 'filename')->andReturn($this->document);
        $this->assertInstanceOf('App\Models\Document', $this->documentManager->getDocument(1, 'url', 'filename'));
    }

    /**
     * @test
     */
    public function testItShouldStoreDocument()
    {
        $user     = m::mock(User::class);
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldReceive('getAttribute')->twice()->with('id')->andReturn(1);
        $user->shouldReceive('getAttribute')->times(3)->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->times(3)->andReturn($user);
        $this->database->shouldReceive('beginTransaction');
        $this->documentRepo->shouldReceive('store')->once()->with($this->document)->andReturn(true);
        $this->database->shouldReceive('commit');
        $this->logger->shouldReceive('info')->once()->with(
            'Document saved successfully.',
            ['for' => 1]
        );
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.document_saved',
            [
                'organization'    => 'orgName',
                'organization_id' => 1
            ]
        );
        $this->assertTrue($this->documentManager->store($this->document));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
