<?php

use Illuminate\Database\Seeder;

class MunicipalitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $municipalities = [
            ['id' => 1, 'district_id' => 1, 'name' => 'Bardagoriya Rural Municipality' ,'name_np' => '', 'wards' => 6],
            ['id' => 2, 'district_id' => 1, 'name' => 'Bhajani Municipality' ,'name_np' => '', 'wards' => 9],
            ['id' => 3, 'district_id' => 1, 'name' => 'Chure Rural Municipality' ,'name_np' => '', 'wards' => 6],
            ['id' => 4, 'district_id' => 1, 'name' => 'Dhangadhi Sub-Metropolitan' ,'name_np' => '', 'wards' => 19],
            ['id' => 5, 'district_id' => 1, 'name' => 'Gauri Ganga Municipality' ,'name_np' => '', 'wards' => 11],
            ['id' => 6, 'district_id' => 1, 'name' => 'Ghodaghodi Municipality' ,'name_np' => '', 'wards' => 12],
            ['id' => 7, 'district_id' => 1, 'name' => 'Godavari Municipality' ,'name_np' => '', 'wards' => 12],
            ['id' => 8, 'district_id' => 1, 'name' => 'Janaki Rural Municipality' ,'name_np' => '', 'wards' => 9],
            ['id' => 9, 'district_id' => 1, 'name' => 'Joshipur Rural Municipality' ,'name_np' => '', 'wards' => 7],
            ['id' => 10, 'district_id' => 1, 'name' => 'Kailari Rural Municipality' ,'name_np' => '', 'wards' => 9],
            ['id' => 11, 'district_id' => 1, 'name' => 'Lamki-Chuha Municipality' ,'name_np' => '', 'wards' => 10],
            ['id' => 12, 'district_id' => 1, 'name' => 'Mohanyal Rural Municipality' ,'name_np' => '', 'wards' => 7],
            ['id' => 13, 'district_id' => 1, 'name' => 'Tikapur Muncipality' ,'name_np' => '', 'wards' => 9],
        ];

        DB::table('municipalities')->insert($municipalities);
    }
}

