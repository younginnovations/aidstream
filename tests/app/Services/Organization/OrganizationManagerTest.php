<?php

namespace Services\Organization;

use Mockery as m;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Logging\Log as Logger;
use Illuminate\Contracts\Logging\Log;
use App\Models\Organization\OrganizationData;
use App\Models\OrganizationPublished;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Core\V201\Repositories\UserRepository;
use App\Services\Organization\OrganizationManager;
use Test\AidStreamTestCase;
use App\Core\Version;
use App\Core\V201\IatiOrganization;
use App\Core\V201\Repositories\Organization\OrganizationRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Organization\Organization;
use App\Models\Settings;
use App\User;

/**
 * Class OrganizationManagerTest
 */
class OrganizationManagerTest extends AidStreamTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->user = m::mock(User::class);
        $this->organization = m::mock(Organization::class);
        $this->settings = m::mock(Settings::class);
        $this->collection = m::mock(Collection::class);
        $this->version = m::mock(Version::class);
        $this->iatiOrganization = m::mock(IatiOrganization::class);
        $this->auth = m::mock(Guard::class);
        $this->logger = m::mock(Logger::class);
        $this->dbLogger = m::mock(Log::class);
        $this->organizationData = m::mock(OrganizationData::class);
        $this->organizationPublished = m::mock(OrganizationPublished::class);
        $this->formBuilder = m::mock(FormBuilder::class);
        $this->userRepository = m::mock(UserRepository::class);
        $this->organizationRepository = m::mock(OrganizationRepository::class);

        $this->version->shouldReceive('getOrganizationElement')->andReturn($this->iatiOrganization);
        $this->iatiOrganization->shouldReceive('getRepository')->andReturn($this->organizationRepository);

        $this->organizationManager = new OrganizationManager(
            $this->version,
            $this->auth,
            $this->organizationData,
            $this->organizationPublished,
            $this->userRepository,
            $this->logger,
            $this->dbLogger,
            $this->formBuilder);
    }

    /** @test */
    public function itShouldReturnOrganizationElement()
    {
        $this->assertInstanceOf('App\Core\V201\IatiOrganization', $this->organizationManager->getOrganizationElement());
    }

    /** @test */
    public function isShouldReturnOrganizations()
    {
        $this->organizationRepository->shouldReceive('getOrganizations')->with('*')->once()->andReturn($this->collection);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->organizationManager->getOrganizations('*'));
    }

    /** @test */
    public function itShouldReturnOrganizationFromId()
    {
        $this->organizationRepository->shouldReceive('getOrganization')->with(1)->once()->andReturn($this->organization);
        $this->assertInstanceOf(Organization::class, $this->organizationManager->getOrganization(1));
    }

    /** @test */
    public function itShouldReturnOrganizationData()
    {
        $this->organizationRepository->shouldReceive('getOrganizationData')->with(1)->once()->andReturn($this->organizationData);
        $this->assertInstanceOf(OrganizationData::class, $this->organizationManager->getOrganizationData(1));
    }

    /** @test */
    public function itShouldReturnOrganizationStatus()
    {
        $this->organizationRepository->shouldReceive('getStatus')->with(1)->once()->andReturn(1);
        $this->assertEquals(1, $this->organizationManager->getStatus(1));
    }

    /** @test */
    public function itShouldUpdateOrganizationStatus()
    {
        $input = ['status' => 1];
        $user = m::mock('App\User');
        $this->organizationRepository->shouldReceive('updateStatus')->once()->with($input, $this->organizationData)->andReturn(true);
        $this->organization->shouldReceive('getAttribute')->once()->with('name')->andReturn('org_name');
        $this->auth->shouldReceive('user')->once()->andReturn($user);
        $user->shouldReceive('getAttribute')->once()->with('organization')->andReturn($this->organization);

        $this->logger->shouldReceive('info')->once()->with('Organization has been Completed');
        $this->logger->shouldReceive('activity')->once()
            ->with(
                "organization.organization_status_changed",
                [
                    'name' => 'org_name',
                    'status' => 'Completed'
                ]
                );
        $this->assertEquals(true, $this->organizationManager->updateStatus($input, $this->organizationData));
    }

    /** @test */
    public function itShouldReturnPublishedFiles()
    {
        $this->organizationRepository->shouldReceive('getPublishedFiles')->with(1)->once()->andReturn($this->collection);
        $this->assertInstanceOf(Collection::class, $this->organizationManager->getPublishedFiles(1));
    }

    /** @test */
    public function itShouldDeleteAndReturnPublishedFileIfFound()
    {
        $this->organizationRepository->shouldReceive('deletePublishedFile')->once()->with(1)->andReturn($this->organizationPublished);
        $this->assertInstanceOf(OrganizationPublished::class, $this->organizationManager->deletePublishedFile(1));
    }

    /** @test */
    public function itShouldUpdatePublishToRegister()
    {
        $this->organizationRepository->shouldReceive('updatePublishToRegister')->once()->with(1)->andReturn(true);
        $this->assertEquals(true, $this->organizationManager->updatePublishToRegister(1));
    }

    /** @test */
    public function itShouldpublishOrganizationToRegistry()
    {
        $this->organizationRepository->shouldReceive('publishToRegistry')->once()->with($this->organization, $this->settings, 'filename')->andReturn(true);
        $this->assertEquals(true, $this->organizationManager->publishToRegistry($this->organization, $this->settings, 'filename'));
    }

    /** @test */
    public function itShouldSaveAndReturnOrganizationPublishedFiles()
    {
        $this->organizationRepository->shouldReceive('saveOrganizationPublishedFiles')->once()->with('filename', 1)->andReturn(true);
        $this->assertEquals(true, $this->organizationManager->saveOrganizationPublishedFiles('filename', 1));
    }

    /** @test */
    public function itShouldgetOrganizationusers()
    {
        $user = m::mock('App\User');
        $userIds = ['userId', 'userId', 'userId'];
        $arrayIterator = new \ArrayIterator([$user, $user, $user]);
        $this->organizationRepository->shouldReceive('getOrganization')->once()->with(1)->andReturn($this->organization);
        $this->organization->shouldReceive('getAttribute')->once()->with('users')->andReturn($arrayIterator);
        $user->shouldReceive('getAttribute')->with('id')->times(3)->andReturn('userId');
        $this->assertEquals($userIds, $this->organizationManager->getOrganizationUsers(1));
    }

    /** @test */
    public function itShouldCheckAndReturnReportingOrganizationMatchingReportingOrganizationIdentifier()
    {
        $this->organizationRepository->shouldReceive('getReportingOrganizations')->with('repOrg')->once()->andReturn($this->organization);
        $this->assertInstanceOf(Organization::class, $this->organizationManager->checkReportingOrganization('repOrg'));
    }

    /** @test */
    public function itShouldReturnOrganizationInformation()
    {
        $formOptions = [
            'method' => 'PUT',
            'url'    => 'dummyUrl',
            'model'  => ['narrative' => 'organizationNarrative']
        ];
        $this->formBuilder->shouldReceive('create')->once()->with('App\Core\V201\Forms\Settings\OrganizationInformation', $formOptions)->andReturn($this->formBuilder);
        $this->assertInstanceOf(FormBuilder::class, $this->organizationManager->viewOrganizationInformation($formOptions));
    }

    /** @test */
    public function itShouldReturnSavedOrganizationInformation()
    {
        $organizationInfo = ['user_identifier' => 'userIdentifier'];

        $this->organization->shouldReceive('getAttribute')->with('user_identifier')->once()->andReturn('userIdentifier');
        $this->auth->shouldReceive('user')->twice()->andReturn($this->user);
        $this->user->shouldReceive('getAttribute')->with('organization')->twice()->andReturn($this->organization);
        $this->organization->shouldReceive('getAttribute')->with('name')->once()->andReturn('organizationName');
        $this->organization->shouldReceive('getAttribute')->with('id')->once()->andReturn(1);
        $this->organizationRepository->shouldReceive('saveOrganizationInformation')->with($organizationInfo, $this->organization)->once()->andReturn(true);
        $this->logger->shouldReceive('info')->with('Settings Updated Successfully.')->once();
        $this->dbLogger->shouldReceive('activity')->with("activity.settings_updated", [
                    'organization'    => 'organizationName',
                    'organization_id' => 1
                ])->once();

        $this->assertTrue($this->organizationManager->saveOrganizationInformation($organizationInfo, $this->organization));
    }

    /** @test */
    public function itShouldReturnTrueOrFalseAfterUpdatingUsername()
    {
        $this->userRepository->shouldReceive('updateUsername')->with('oldUsername', 'newUsername');
        $this->logger->shouldReceive('info')->with('Username has been updated.', ['for ' => null]);

        $this->assertTrue($this->organizationManager->updateUsername('oldUsername', 'newUsername'));
    }

    /** @test */
    public function itShouldReturnTrueOnSuccessfullyDeletingElement()
    {
        $this->organizationRepository->shouldReceive('deleteElement')->with($this->organizationData, 'element')->andReturn($this->organizationData);
        $this->logger->shouldReceive('info')->with('Organization element element has been deleted.', ['for ' => 1])->once();
        $this->organizationData->shouldReceive('getAttribute')->with('id')->once()->andReturn(1);

        $this->auth->shouldReceive('user')->twice()->andReturn($this->user);
        $this->user->shouldReceive('getAttribute')->with('organization')->twice()->andReturn($this->organization);
        $this->organization->shouldReceive('getAttribute')->with('name')->once()->andReturn('orgName');
        $this->organization->shouldReceive('getAttribute')->with('id')->once()->andReturn(1);
        $this->logger->shouldReceive('activity')->with('organization.organization_element_deleted',
            [
                'element' => 'element',
                'organization' => 'orgName',
                'organization_id' => 1
            ]);

        $this->assertTrue($this->organizationManager->deleteElement($this->organizationData, 'element'));
    }

    /** @test */
    public function itShouldReturnOrganizationDataAfterResettingOrganizationWorkflow()
    {
        $this->organizationRepository->shouldReceive('resetOrganizationWorkflow')->with($this->organizationData)->once()->andReturn($this->organizationData);
        $this->assertInstanceOf(OrganizationData::class, $this->organizationManager->resetOrganizationWorkflow($this->organizationData));
    }

    /** @test */
    public function itShouldReturnPublishedOrganizationData()
    {
        $this->organizationRepository->shouldReceive('getPublishedOrganizationData')->once()->with(1)->andReturn($this->organizationPublished);
        $this->assertInstanceOf(OrganizationPublished::class, $this->organizationManager->getPublishedOrganizationData(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
