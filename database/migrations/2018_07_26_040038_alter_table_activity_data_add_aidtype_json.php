<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableActivityDataAddAidtypeJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // Schema::table('activity_data', function (Blueprint $table) {
        //     $table->json('default_aid_type')->nullable()->change();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        // Schema::table('activity_data', function (Blueprint $table) {
        //     $table->varchar('default_aid_type','255')->nullable()->change();
        // });
    }
}
