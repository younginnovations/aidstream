<?php namespace Test\SuperAdmin\Repositories;

use App\Models\Organization\Organization;
use App\Models\SuperAdmin\UserGroup;
use App\SuperAdmin\Repositories\OrganizationGroup;
use App\User;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\DatabaseManager;
use Mockery as m;
use Auth;
use Test\AidStreamTestCase;

/**
 * Class OrganizationGroupTest
 * @package Test\SuperAdmin\Repositories
 */
class OrganizationGroupTest extends AidStreamTestCase
{
    protected $user;
    protected $organization;
    protected $database;
    protected $logger;
    protected $loggerInterface;
    protected $userGroup;
    protected $organizationGroup;

    public function setUp()
    {
        parent::setUp();
        $this->user              = m::mock(User::class);
        $this->userGroup         = m::mock(UserGroup::class);
        $this->organization      = m::mock(Organization::class);
        $this->database          = m::mock(DatabaseManager::class);
        $this->logger            = m::mock(Log::class);
        $this->loggerInterface   = m::mock('Psr\Log\LoggerInterface');
        $this->organizationGroup = new OrganizationGroup(
            $this->user,
            $this->userGroup,
            $this->organization,
            $this->database,
            $this->loggerInterface,
            $this->logger
        );
    }

    public function testItShouldGetAllOrganizationGroups()
    {
        $this->userGroup->shouldReceive('all')->andReturnSelf();
        $this->assertInstanceOf('App\Models\SuperAdmin\UserGroup', $this->organizationGroup->getOrganizationGroups());
    }

    public function testItShouldGetOrganizationGroupUserById()
    {
        $this->userGroup->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive('getAttribute')->once()->with('user_id')->andReturn(1);
        $this->userGroup->shouldReceive('with->whereUserId->first->toArray')->andReturn([]);
        $this->assertTrue(is_array($this->organizationGroup->getOrganizationGroupUserById(1)));
    }

    public function testItShouldSaveOrganizationGroupDetails()
    {
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->userGroup->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf()->shouldReceive('getAttribute')->once()->with('user_id')->andReturn(1);
        $this->user->shouldReceive('firstOrNew')->once()->with(['id' => 1])->andReturnSelf();
        $this->user->shouldReceive('fill->save')->andReturn(true);
        $this->user->shouldReceive('getAttribute')->twice()->with('id')->andReturn(1);
        $this->userGroup->shouldReceive('firstOrNew')->once()->with(['user_id' => 1])->andReturnSelf();
        $this->userGroup->shouldReceive('fill->save')->andReturn(true);
        $this->userGroup->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $this->loggerInterface->shouldReceive('info')->once()->with('Group information Updated');
        $this->logger->shouldReceive('activity')->once()->with('group_updated', ['group_id' => 1, 'user_id' => 1]);
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $data = [
            'group_admin_information' => [
                [
                    'first_name' => 'firstName',
                    'last_name'  => 'lastName',
                    'username'   => 'userName',
                    'email'      => 'email',
                    'password'   => 'password',
                    'role_id'    => 1
                ]
            ],
            'new_organization_group'  => [
                [
                    'group_name'       => 'groupName',
                    'organizations'    => 'organizations',
                    'group_identifier' => 'groupIdentifier',
                    'user_id'          => 1
                ]
            ]
        ];
        $this->assertNull($this->organizationGroup->save($data, 1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}