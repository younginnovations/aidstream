<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RoleTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => 1, 'role' => 'admin' , 'created_at' => '2015-09-01 11:42:29', 'updated_at' => '2015-09-01 11:43:29'],
            ['id' => 2, 'role' => 'user', 'created_at' => '2015-09-01 11:42:29', 'updated_at' => '2015-09-01 11:43:29'],
            ['id' => 3, 'role' => 'superadmin', 'created_at' => '2015-09-01 11:42:29', 'updated_at' => '2015-09-01 11:43:29'],
            ['id' => 4, 'role' => 'groupadmin', 'created_at' => '2015-09-01 11:42:29', 'updated_at' => '2015-09-01 11:43:29'],
        ];

        DB::table('role')->insert($roles);
    }

}