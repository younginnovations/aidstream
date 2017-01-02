<?php

use App\Models\SystemVersion;

/**
 * Class SystemVersionTableSeeder
 */
class SystemVersionTableSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $versions = ['Core', 'Lite'];

        // Already seeded within the migration.
        // $this->seed($versions);
    }

    /**
     * Seed the SystemVersions table with the available versions.
     * @param $versions
     */
    protected function seed($versions)
    {
        array_walk($versions, function ($version) {
            SystemVersion::create(['system_version' => $version]);
        });
    }
}
