<?php namespace App\Migration\Elements;

class UserPermission
{
    public function format(array $permissionMetaData)
    {
        $add     = '';
        $edit    = '';
        $delete  = '';
        $publish = '';

        $add_activity     = $permissionMetaData["\x00*\x00add_activity"];
        $edit_activity    = $permissionMetaData["\x00*\x00edit_activity"];
        $delete_activity  = $permissionMetaData["\x00*\x00delete_activity"];
        $publish_activity = $permissionMetaData["\x00*\x00publish"];

        if ($add_activity == '1') {
            $add = 'add_activity';
        }
        if ($edit_activity == '1') {
            $edit = 'edit_activity';
        }
        if ($delete_activity == '1') {
            $delete = 'delete_activity';
        }
        if ($publish_activity == '1') {
            $publish = 'publish_activity';
        }
        $newPermissionFormat = [
            'add'     => $add,
            'edit'    => $edit,
            'delete'  => $delete,
            'publish' => $publish
        ];

        return $newPermissionFormat;
    }
}