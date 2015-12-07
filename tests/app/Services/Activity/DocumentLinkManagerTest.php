<?php namespace Test\app\Services\Activity;

use App\Core\V201\Repositories\Activity\DocumentLink;
use App\Core\Version;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use App\Services\Activity\DocumentLinkManager;
use App\User;
use Illuminate\Auth\Guard;
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

    public function SetUp()
    {
        parent::setUp();
        $this->version          = m::mock(Version::class);
        $this->auth             = m::mock(Guard::class);
        $this->dbLogger         = m::mock(Log::class);
        $this->logger           = m::mock(LoggerInterface::class);
        $this->documentLinkRepo = m::mock(DocumentLink::class);
        $this->activity         = m::mock(Activity::class);
        $this->database         = m::mock(DatabaseManager::class);
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
        $orgModel = m::mock(Organization::class);
        $orgModel->shouldReceive('getAttribute')->once()->with('name')->andReturn('orgName');
        $orgModel->shouldREceive('getAttribute')->once()->with('id')->andReturn(1);
        $user = m::mock(User::class);
        $user->shouldReceive('getAttribute')->twice()->with('organization')->andReturn($orgModel);
        $this->auth->shouldReceive('user')->twice()->andReturn($user);
        $activityModel = $this->activity;
        $activityModel->shouldReceive('getAttribute')->with('id')->andreturn(1);
        $activityModel->shouldReceive('getAttribute')->once()->with('document_link')->andReturn(
            'testDocumentLink'
        );
        $this->documentLinkRepo->shouldReceive('update')
                               ->once()
                               ->with(['document_link' => 'testDocumentLink'], $activityModel)
                               ->andReturn(true);
        $this->logger->shouldReceive('info')->once()->with(
            'Activity Document Link updated!',
            ['for' => 'testDocumentLink']
        );
        $this->dbLogger->shouldReceive('activity')->once()->with(
            'activity.document_link',
            [
                'activity_id'     => 1,
                'organization'    => 'orgName',
                'organization_id' => 1
            ]
        );
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $this->assertTrue(
            $this->documentLinkManager->update(
                ['document_link' => 'testDocumentLink'],
                $activityModel
            )
        );
    }

    public function testItShouldGetDocumentLinkDataWithCertainId()
    {
        $this->documentLinkRepo->shouldReceive('getDocumentLinkData')->once()->with(1)->andReturn($this->activity);
        $this->assertInstanceOf(
            'App\Models\Activity\Activity',
            $this->documentLinkManager->getDocumentLinkData(1)
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
