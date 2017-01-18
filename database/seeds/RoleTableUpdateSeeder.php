<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RoleTableUpdateSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->where('id', 2)->update(['role' => 'Publisher', 'permissions' => '["add_activity", "edit_activity", "delete_activity", "publish_activity"]']);

        $roles = [
            ['id' => 5, 'role' => 'Administrator', 'permissions' => '["add_activity", "edit_activity", "delete_activity", "publish_activity", "settings"]', 'created_at' => '2015-09-01 11:42:29', 'updated_at' => '2015-09-01 11:43:29'],
            ['id' => 6, 'role' => 'Editor', 'permissions' => '["add_activity", "edit_activity"]', 'created_at' => '2015-09-01 11:42:29', 'updated_at' => '2015-09-01 11:43:29'],
            ['id' => 7, 'role' => 'Viewer', 'permissions' => '[]', 'created_at' => '2015-09-01 11:42:29', 'updated_at' => '2015-09-01 11:43:29'],
        ];

        DB::table('role')->insert($roles);
    }

}
