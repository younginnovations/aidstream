<?php

use App\Models\SystemVersion;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('system_version');
            $table->timestamps();
        });

        $versions = ['Core', 'Lite'];

        array_walk($versions, function ($version) {
            SystemVersion::create(['system_version' => $version]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('system_versions');
    }
}
