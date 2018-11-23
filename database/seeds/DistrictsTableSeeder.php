<?php

use Illuminate\Database\Seeder;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $districts = [
            ['id' => 1, 'name' => 'Kailali' , 'province_no' => '7'],         
        ];

        DB::table('districts')->insert($districts);
    }
}

