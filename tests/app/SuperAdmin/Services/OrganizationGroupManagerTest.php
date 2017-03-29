<?php namespace Test\SuperAdmin\Services;

use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Models\Organization\Organization;
use App\Models\SuperAdmin\UserGroup;
use App\SuperAdmin\Services\OrganizationGroupManager;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Psr\Log\LoggerInterface;
use Test\AidStreamTestCase;

/**
 * Class OrganizationGroupManagerTest
 * @package Test\SuperAdmin\Services
 */
class OrganizationGroupManagerTest extends AidStreamTestCase
{
    protected $orgGroupInterface;
    protected $userGroup;
    protected $organization;
    protected $orgGroupManager;
    protected $databaseManager;
    protected $logger;
    protected $organizationRepository;

    public function setUp()
    {
        parent::setUp();
        $this->orgGroupInterface      = m::mock('App\SuperAdmin\Repositories\SuperAdminInterfaces\OrganizationGroup');
        $this->userGroup              = m::mock(UserGroup::class);
        $this->organization           = m::mock(Organization::class);
        $this->databaseManager        = m::mock(DatabaseManager::class);
        $this->logger                 = m::mock(LoggerInterface::class);
        $this->organizationRepository = m::mock(OrganizationRepository::class);
        $this->orgGroupManager        = new OrganizationGroupManager(
            $this->orgGroupInterface,
            $this->userGroup,
            $this->organization,
            $this->databaseManager,
            $this->logger,
            $this->organizationRepository
        );
    }

    public function testItShouldGetAllOrganizationGroups()
    {
        $this->orgGroupInterface->shouldReceive('getOrganizationGroups')->andReturnSelf();
        $this->assertInstanceOf('App\SuperAdmin\Repositories\SuperAdminInterfaces\OrganizationGroup', $this->orgGroupManager->getOrganizationGroups());
    }

    public function testItShouldGetAllOrganizationGroupUserById()
    {
        $this->orgGroupInterface->shouldReceive('getOrganizationGroupUserById')->once()->with(1)->andReturnSelf();
        $this->assertInstanceOf('App\SuperAdmin\Repositories\SuperAdminInterfaces\OrganizationGroup', $this->orgGroupManager->getOrganizationGroupUserById(1));
    }

    public function testItShouldSaveOrganizationUserGroup()
    {
        $this->orgGroupInterface->shouldReceive('save')->once()->with([], 1)->andReturnSelf();
        $this->assertInstanceOf('App\SuperAdmin\Repositories\SuperAdminInterfaces\OrganizationGroup', $this->orgGroupManager->save([], 1));
    }

    public function testItShouldGetGroupsByUserId()
    {
        $a = m::mock(UserGroup::class);
        $this->userGroup->shouldReceive('whereUserId->first')->andReturn($a);
        $a->shouldReceive('getAttribute')->once()->with('assigned_organizations')->andReturn([1]);
        $this->organization->shouldReceive('whereIn->with->orderBy->get')->andReturn(m::mock('\Illuminate\Database\Eloquent\Collection'));
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $this->orgGroupManager->getGroupsByUserId(1));
    }

    public function testItShouldGetOrganizationGroupModelForUpdate()
    {
        $this->orgGroupInterface->shouldReceive('getOrganizationGroupUserById')->once()->with(1)->andReturn(['assigned_organizations' => 'assignedOrg', 'user' => 'user']);
        $data = [
            "new_organization_group"  => [
                0 => [
                    "assigned_organizations" => "assignedOrg",
                    "user"                   => "user",
                    "organizations"          => "assignedOrg"
                ]
            ],
            "group_admin_information" => [
                0 => "user"
            ]
        ];

        $this->assertEquals($data, $this->orgGroupManager->getModelForUpdate(1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
