<?php namespace Test\Elements;

use App\Migration\Elements\UserPermission;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\UserPermissionDataProvider;

class UserPermissionTest extends AidStreamTestCase
{
    use UserPermissionDataProvider;

    protected $testInput = [
        "__PHP_Incomplete_Class_Name" => "Iati_WEP_UserPermission",
        "\x00*\x00add_activity"       => '',
        "\x00*\x00edit_activity"      => '',
        "\x00*\x00delete_activity"    => '',
        "\x00*\x00publish"            => ''
    ];
    protected $expectedOutput = [];
    protected $userPermission;
    protected $permissions = ['add' => 'add_activity', 'edit' => 'edit_activity', 'delete' => 'delete_activity', 'publish' => 'publish_activity'];

    public function setUp()
    {
        parent::setUp();

        $this->userPermission = new UserPermission();
    }

    /** {@test} */
    public function itShouldFormatUserDataWithAddPermission()
    {
        $this->executeTestForSpecificPermission('add');
    }

    /** {@test} */
    public function itShouldFormatUserDataWithEditPermission()
    {
        $this->executeTestForSpecificPermission('edit');
    }

    /** {@test} */
    public function itShouldFormatDataWithDeletePermission()
    {
        $this->executeTestForSpecificPermission('delete');
    }

    /** {@test} */
    public function itShouldFormatDataWithPublishPermission()
    {
        $this->testInput = $this->getTestInputWithSinglePermission($this->permissions['publish'], $this->testInput);
        $this->formatUserPermissionData();
        $this->assertEquals($this->expectedOutput, $this->userPermission->format($this->testInput));
    }

    /** {@test} */
    public function itShouldFormatDataWithAllPermissions()
    {
        $this->testInput = $this->getTestInputWithAllPermissions($this->permissions, $this->testInput);
        $this->formatUserPermissionData();
        $this->assertEquals($this->expectedOutput, $this->userPermission->format($this->testInput));
    }

    protected function executeTestForSpecificPermission($permission)
    {
        $this->testInput = $this->getTestInputWithSinglePermission($this->permissions[$permission], $this->testInput);
        $this->formatUserPermissionData();

        $this->assertEquals($this->expectedOutput, $this->userPermission->format($this->testInput));
    }

    protected function formatUserPermissionData()
    {
        foreach (array_except($this->testInput, '__PHP_Incomplete_Class_Name') as $key => $value) {
            $outputKey = (substr(explode('_', $key)[0], 3));

            if ($key === "\x00*\x00publish") {
                $this->expectedOutput[$outputKey] = $value ? $this->permissions['publish'] : '';
            } else {
                $this->expectedOutput[$outputKey] = $value ? substr($key, 3) : '';
            }
        }
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
