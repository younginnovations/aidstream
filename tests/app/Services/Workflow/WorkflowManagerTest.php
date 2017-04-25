<?php

namespace Tests\App\Services\Workflow;

use App\Services\Workflow\SegmentationChangeHandler;
use \Mockery as m;
use App\Services\PerfectViewer\PerfectViewerManager;
use App\Services\Twitter\TwitterAPI;
use Psr\Log\LoggerInterface;
use App\Services\Workflow\DataProvider\OrganizationDataProvider;
use App\Services\Xml\Providers\XmlServiceProvider;
use App\Services\Activity\ActivityManager;
use App\Services\Organization\OrganizationManager;
use App\Services\Workflow\WorkflowManager;
use App\Models\Activity\Activity;
use Test\AidStreamTestCase;
use App\Services\Publisher\Publisher;
use App\Models\Organization\Organization;
use App\Models\Settings;
use App\Core\Version;
use App\Core\V202\IatiActivity;
use App\Core\V202\IatiOrganization;
use App\Models\ActivityPublished;

/**
 * Class WorkflowManagerTest
 */
class WorkflowManagerTest extends AidStreamTestCase
{
    public function setUp()
    {
        parent::setup();

        $this->activityPublished         = m::mock(ActivityPublished::class);
        $this->organizationManager       = m::mock(OrganizationManager::class);
        $this->activityManager           = m::mock(ActivityManager::class);
        $this->xmlServiceProvider        = m::mock(XmlServiceProvider::class);
        $this->organizationDataProvider  = m::mock(OrganizationDataProvider::class);
        $this->publisher                 = m::mock(Publisher::class);
        $this->logger                    = m::mock(LoggerInterface::class);
        $this->twitter                   = m::mock(TwitterAPI::class);
        $this->perfectViewerManager      = m::mock(PerfectViewerManager::class);
        $this->activity                  = m::mock(Activity::class);
        $this->organization              = m::mock(Organization::class);
        $this->settings                  = m::mock(Settings::class);
        $this->version                   = m::mock(Version::class);
        $this->iatiActivity              = m::mock(IatiActivity::class);
        $this->iatiOrganization          = m::mock(IatiOrganization::class);
        $this->segmentationChangeHandler = m::mock(SegmentationChangeHandler::class);
        $this->workflowManager           = new WorkflowManager(
            $this->organizationManager,
            $this->activityManager,
            $this->xmlServiceProvider,
            $this->organizationDataProvider,
            $this->publisher,
            $this->logger,
            $this->twitter,
            $this->segmentationChangeHandler,
            $this->perfectViewerManager
        );
    }

    /** @test */
    public function itShouldReturnAnActivity()
    {
        $this->organizationDataProvider->shouldReceive('findActivity')->with(1)->once()->andReturn($this->activity);
        $this->assertInstanceOf(Activity::class, $this->workflowManager->findActivity(1));
    }

    /** @test */
    public function itShouldValidateActivityAgainstActivityXmlSchema()
    {
        $this->activity->shouldReceive('getAttribute')->with('organization')->once()->andReturn($this->organization);
        $this->organization->shouldReceive('getAttribute')->with('settings')->once()->andReturn($this->settings);
        $this->settings->shouldReceive('getAttribute')->with('version')->once()->andReturn($this->version);
        $this->organizationManager->shouldReceive('getOrganizationElement')->once()->andReturn($this->iatiOrganization);
        $this->activityManager->shouldReceive('getActivityElement')->once()->andReturn($this->iatiActivity);
        $this->xmlServiceProvider->shouldReceive('initializeValidator')->with($this->version)->andReturnSelf();
        $this->xmlServiceProvider->shouldReceive('validate')->with($this->activity, $this->iatiOrganization, $this->iatiActivity)->andReturn([]);

        $this->assertEquals([], $this->workflowManager->validate($this->activity));
    }

    /** @test */
    public function itShouldUpdateActivityStatus()
    {
        $this->activityManager->shouldReceive('updateStatus')->with([], $this->activity)->andReturn(true);
        $this->assertEquals(true, $this->workflowManager->update([], $this->activity));
    }

    /**
     * @depends itShouldUpdateActivityStatus
     */
    public function itShouldPublishActivityToIatiRegistry()
    {
        $settings = [['publish_files' => 'yes']];

        $this->activity->shouldReceive('getAttribute')->with('organization')->once()->andReturn($this->organization);
        $this->organization->shouldReceive('getAttribute')->with('settings')->once()->andReturn($this->settings);
        $this->settings->shouldReceive('getAttribute')->with('version')->once()->andReturn($this->version);
        $this->settings->shouldReceive('offsetGet')->with('registry_info')->andReturn($settings);
        $this->activity->shouldReceive('getAttribute')->with('id')->once()->andReturn(1);
        $this->settings->shouldReceive('getAttribute')->with('publishing_type')->once()->andReturn('unsegmented');
        $this->organizationDataProvider->shouldReceive('fileBeingPublished')->with(1)->once()->andReturn($this->activityPublished);
        $this->publisher->shouldReceive('publishFile')->with($settings, $this->activityPublished, $this->organization, 'unsegmented');
        $this->activity->shouldReceive('getAttribute')->with('published_to_registry')->once();
        $this->activity->shouldReceive('setAttribute')->with('published_to_registry', 1)->once();
        $this->activityManager->shouldReceive('activityInRegistry')->with($this->activity)->once();
        $this->twitter->shouldReceive('post')->with($this->settings, $this->organization)->once();
        $this->perfectViewerManager->shouldReceive('createSnapshot')->with($this->activity)->once();

        $this->organizationManager->shouldReceive('getOrganizationElement')->once()->andReturn($this->iatiOrganization);
        $this->activityManager->shouldReceive('getActivityElement')->once()->andReturn($this->iatiActivity);
        $this->xmlServiceProvider->shouldReceive('generate')->once()->with($this->activity, $this->iatiOrganization, $this->iatiActivity);
        $this->xmlServiceProvider->shouldReceive('initializeGenerator')->with($this->version)->once()->andReturnSelf();
        $this->activity->shouldReceive('save')->once()->andReturn(true);

        $this->assertTrue($this->workflowManager->publish($this->activity, []));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
