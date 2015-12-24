<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultFieldValuesToActivityDataTable extends Migration
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
                $table->json('default_field_values')->nullable();
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
                $table->dropColumn('default_field_values');
            }
        );
    }
}
