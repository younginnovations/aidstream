<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHumanitarianScopeToActivityDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'activity_data',
            function (Blueprint $table) {
                $table->json('humanitarian_scope')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'activity_data',
            function (Blueprint $table) {
                $table->dropColumn('humanitarian_scope');
            }
        );
    }
}
