<?php

namespace Services\Workflow\DataProvider;

use Test\AidStreamTestCase;
use \Mockery as m;
use App\Services\Workflow\DataProvider\OrganizationDataProvider;
use App\Models\OrganizationPublished;
use App\Models\ActivityPublished;
use App\Models\Activity\Activity;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Settings;

/**
 * Class OrganizationDataProviderTest
 */
class OrganizationDataProviderTest extends AidStreamTestCase
{
    public function setUp()
    {
        parent::setup();

        $this->organization = m::mock(Organization::class);
        $this->activity = m::mock(Activity::class);
        $this->activityPublished = m::mock(ActivityPublished::class);
        $this->organizationPublished = m::mock(OrganizationPublished::class);
        $this->collection = m::mock(Collection::class);
        $this->settings = m::mock(Settings::class);

        $this->organizationDataProvider = new OrganizationDataProvider($this->organization,
            $this->activity,
            $this->activityPublished,
            $this->organizationPublished);
    }

    /** @test */
    public function itShouldReturnOrganization()
    {
        $this->organization->shouldReceive('findOrFail')->with(1)->once()->andReturn($this->organization);
        $this->assertInstanceOf(Organization::class, $this->organizationDataProvider->find(1));
    }

    /** @test */
    public function itShouldReturnCurrentStatus()
    {
        $currentStatus = [];
        $file = [ 'filename' => ['included_activities' => 1, 'published_status' => 1]];

        $this->collection->shouldReceive('isEmpty')->once()->andReturn(false);
        $this->collection->shouldReceive('each')->andReturnUsing(function ($file) use ($currentStatus) {
            return $file;
        });

        $this->assertEquals([], $this->organizationDataProvider->getCurrentStatus($this->collection));
    }

    /** @test */
    public function itShouldDeleteOldData()
    {
        $this->activityPublished->shouldReceive('query->where->where->delete')->andReturn(true);
        $this->assertEquals(null, $this->organizationDataProvider->deleteOldData('filename', 1));
    }

    /** @test */
    public function itShouldUpdateStatus()
    {
        $this->organization->shouldReceive('findOrFail')->with(1)->once()->andReturn($this->organization);

        $changes = ['changes' => ['filename1' => '1.xml', 'filename2' => '2.xml']];

        $this->activityPublished->shouldReceive('query->where->where->first')->twice()->andReturn($this->activityPublished);
        $this->activityPublished->shouldReceive('setAttribute')->with('published_to_register', 1)->twice()->andReturnSelf();

        $this->activityPublished->shouldReceive('save')->twice()->andReturn(true);

        $this->assertEquals(null, $this->organizationDataProvider->updateStatus($changes, 1));
    }

    /** @test */
    public function itShouldReturnActivity()
    {
        $this->activity->shouldReceive('findOrFail')->with(1)->once()->andReturn($this->activity);
        $this->assertInstanceOf(Activity::class, $this->organizationDataProvider->findActivity(1));
    }

    /** @test */
    public function itShouldReturnActivityPublishedAfterPublishingFile()
    {
        $registryInfo = [['publisher_id' => 1]];

        $this->activity->shouldReceive('findOrFail')->with(1)->once()->andReturn($this->activity);
        $this->activity->shouldReceive('getAttribute')->with('organization')->once()->andReturn($this->organization);
        $this->organization->shouldReceive('getAttribute')->with('settings')->once()->andReturn($this->settings);
        $this->settings->shouldReceive('getAttribute')->with('registry_info')->once()->andReturn($registryInfo);
        $this->settings->shouldReceive('getAttribute')->with('publishing_type')->once()->andReturn('unsegmented');
        $this->activity->shouldReceive('getAttribute')->with('organization_id')->once()->andReturn(1);

        $this->activityPublished->shouldReceive('query->where->where->latest->first')->once()->andReturn($this->activityPublished);

        $this->assertInstanceOf(ActivityPublished::class, $this->organizationDataProvider->fileBeingPublished(1));
    }

    /** @test */
    public function itShouldUnsetPublishedFlag()
    {
        $changes = ['previous' => ['file1' => '1.xml', 'file2' => '2.xml']];

        $this->activityPublished->shouldReceive('where->delete')->twice()->andReturn(true);

        $this->assertEquals(null, $this->organizationDataProvider->unsetPublishedFlag($changes));
    }


    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
