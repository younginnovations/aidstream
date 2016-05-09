<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\DocumentLink;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Activity\ActivityDocumentLink;
use App\Models\Organization\Organization;
use App\Services\Activity\DocumentLinkManager;
use App\Services\DocumentManager;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log;
use Psr\Log\LoggerInterface;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class DocumentLinkManagerTest
 * @package Test\app\Services\Activity
 */
class DocumentLinkManagerTest extends AidStreamTestCase
{
    protected $version;
    protected $auth;
    protected $dbLogger;
    protected $logger;
    protected $documentLinkRepo;
    protected $documentLinkManager;
    protected $activity;
    protected $database;
    protected $documentLinkModel;
    protected $documentManager;

    public function SetUp()
    {
        parent::setUp();
        $this->version           = m::mock(Version::class);
        $this->auth              = m::mock(Guard::class);
        $this->dbLogger          = m::mock(Log::class);
        $this->logger            = m::mock(LoggerInterface::class);
        $this->documentLinkRepo  = m::mock(DocumentLink::class);
        $this->activity          = m::mock(Activity::class);
        $this->documentLinkModel = m::mock(ActivityDocumentLink::class);
        $this->documentManager   = m::mock(DocumentManager::class);
        $this->database          = m::mock(DatabaseManager::class);
        $this->version->shouldReceive('getActivityElement->getDocumentLink->getRepository')->andReturn(
            $this->documentLinkRepo
        );
        $this->documentLinkManager = new DocumentLinkManager(
            $this->version,
            $this->auth,
            $this->database,
            $this->dbLogger,
            $this->logger
        );
    }

    public function testItShouldUpdateActivityDocumentLink()
    {
        $documentLinkData = [['url' => 'testUrl']];
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();

        $this->documentLinkModel->shouldReceive('getAttribute')->with('exists')->andReturn(false);
        $this->documentLinkModel->shouldReceive('getAttribute')->with('activity_id')->andReturn(1);
        $this->documentLinkModel->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $this->documentLinkModel->shouldReceive('getAttribute')->once()->with('activity')->andReturn($this->activity);
        $this->activity->shouldReceive('getAttribute')->once()->with('identifier')->andReturn(['activity_identifier' => 1]);

        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldREceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $this->documentLinkRepo->shouldReceive('update')
                               ->once()
                               ->with($documentLinkData, $this->documentLinkModel)
                               ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Activity Document Link saved!',
            ['for' => $documentLinkData]
        );

        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.document_link_saved',
            [
                'activity_id'      => 1,
                'document_link_id' => 1,
                'organization'     => 'orgName',
                'organization_id'  => 1
            ]
        );
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $this->assertTrue(
            $this->documentLinkManager->update(
                $documentLinkData,
                $this->documentLinkModel
            )
        );
    }

    public function testItShouldGetDocumentLinkWithCertainIdAndActivityId()
    {
        $this->documentLinkRepo->shouldReceive('getDocumentLink')->once()->with(1, 2)->andReturn($this->documentLinkModel);
        $this->assertInstanceOf(
            'App\Models\Activity\ActivityDocumentLink',
            $this->documentLinkManager->getDocumentLink(1, 2)
        );
    }

    public function testItShouldGetDocumentLinksWithCertainActivityId()
    {
        $this->documentLinkRepo->shouldReceive('getDocumentLinks')->once()->with(2)->andReturn([]);
        $this->assertEquals([], $this->documentLinkManager->getDocumentLinks(2));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
