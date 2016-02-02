<?php namespace Test\Elements\DataProviders;


trait UserPermissionDataProvider
{
    protected function getTestInputWithSinglePermission($permission, $holder)
    {
        if ($permission === 'publish_activity') {
            $key = sprintf("\x00*\x00%s", 'publish');
        } else {
            $key = sprintf("\x00*\x00%s", $permission);
        }

        $holder[$key] = "1";

        return $holder;
    }

    protected function getTestInputWithAllPermissions($permissions, $holder)
    {
        foreach ($permissions as $index => $permission) {
            $holder = $this->getTestInputWithSinglePermission($permissions[$index], $holder);

        }

        return $holder;
    }
}
