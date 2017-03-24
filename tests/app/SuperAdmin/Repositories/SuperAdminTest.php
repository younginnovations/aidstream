<?php namespace Test\SuperAdmin\Repositories;

use App\Models\Organization\Organization;
use App\Models\Settings;
use App\SuperAdmin\Repositories\SuperAdmin;
use App\User;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use Mockery as m;
use Test\AidStreamTestCase;

/**
 * Class SuperAdminTest
 * @package Test\SuperAdmin\Repositories
 */
class SuperAdminTest extends AidStreamTestCase
{
    protected $user;
    protected $superAdmin;
    protected $settings;
    protected $organization;
    protected $database;
    protected $logger;
    protected $loggerInterface;

    public function setUp()
    {
        parent::setUp();
        $this->user            = m::mock(User::class);
        $this->settings        = m::mock(Settings::class);
        $this->organization    = m::mock(Organization::class);
        $this->database        = m::mock(DatabaseManager::class);
        $this->logger          = m::mock(Log::class);
        $this->loggerInterface = m::mock('Psr\Log\LoggerInterface');
        $this->superAdmin      = new SuperAdmin(
            $this->user,
            $this->settings,
            $this->organization,
            $this->database,
            $this->loggerInterface,
            $this->logger
        );
    }

    public function testItShouldGetAllOrganizations()
    {
        $this->organization->shouldReceive('with->orderBy->get')->andReturn(
            m::mock('\Illuminate\Database\Eloquent\Collection')
        );
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $this->superAdmin->getOrganizations());
    }

    public function testItShouldReturnOrganizationDataWithSpecificId()
    {
        $this->organization->shouldReceive('findOrFail')->once()->with(1)->andReturn(
            m::mock('\Illuminate\Database\Eloquent\Collection')
        );
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $this->superAdmin->getOrganizationById(1));
    }

    public function testItShouldReceiveOrganizationDataAndUserDataWithSpecificId()
    {
        $this->organization->shouldReceive('join->where->where->select->get->toArray')->andReturn([]);
        $this->assertTrue(is_array($this->superAdmin->getOrganizationUserById(1)));
    }

    public function testItShouldSaveOrganizationDetails()
    {
        $collection = m::mock(Collection::class);
        $this->database->shouldReceive('beginTransaction')->once()->andReturnSelf();
        $this->organization->shouldReceive('findOrFail')->once()->with(1)->andReturnSelf();
        $this->organization->shouldReceive('getAttribute')->times(4)->with('id')->andReturn('1');
        $this->organization->shouldReceive('fill->save')->andreturn(true);
        $this->organization->shouldReceive('update')->andReturn(true);
        $this->user->shouldReceive('update')->andReturn(true);
        $this->user->shouldReceive('where')->andReturnSelf();
        $this->user->shouldReceive('first')->andReturnSelf();
        $this->user->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $collection->shouldReceive('where->first')->andReturn($this->user);
        $this->organization->shouldReceive('getAttribute')->with('users')->andReturn($collection);
        $this->user->shouldReceive('fill->save')->andReturn(true);
        $this->settings->shouldReceive('firstOrNew')->once()->with(['organization_id' => 1])->andReturnSelf();
        $this->settings->shouldReceive('fill->save')->andReturn(true);
        $this->loggerInterface->shouldReceive('info')->once()->with('Organization information Updated');
        $this->logger->shouldReceive('activity')->once()->with(
            'activity.organization_updated',
            ['user_id' => 1, 'organization_id' => 1]
        );
        $this->database->shouldReceive('commit')->once()->andReturnSelf();
        $data = [
            'organization_information' => [
                [
                    'name'            => 'orgName',
                    'address'         => 'address',
                    'user_identifier' => 'identifier'
                ]
            ],
            'admin_information'        => [
                [
                    'first_name' => 'firstName',
                    'last_name'  => 'lastName',
                    'username'   => 'userName',
                    'email'      => 'email',
                    'password'   => 'password'
                ]
            ],
            'default_field_values'     => 'defaultFieldValues',
            'default_field_groups'     => 'defaultFieldGroups'
        ];
        $this->assertNull($this->superAdmin->saveOrganization($data, 1));
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}