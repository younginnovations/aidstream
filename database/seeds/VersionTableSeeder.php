<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class VersionTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $versions = [
            ['id' => 1, 'version' => 'V201' , 'created_at' => '2015-09-01 11:42:29', 'updated_at' => '2015-09-01 11:43:29'],
            ['id' => 2, 'version' => 'V202', 'created_at' => '2015-09-01 11:42:29', 'updated_at' => '2015-09-01 11:43:29'],
        ];

        DB::table('versions')->insert($versions);
    }

}