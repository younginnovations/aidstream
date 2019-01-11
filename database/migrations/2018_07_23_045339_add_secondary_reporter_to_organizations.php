<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecondaryReporterToOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Add Secondary Reporter Flag on v2.03 on Organizations Table
        Schema::table('organizations', function (Blueprint $table) {
            $table->integer('secondary_reporter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table(
            'organizations',
            function (Blueprint $table) {
                $table->dropColumn('secondary_reporter');
            }
        );
    }
}
