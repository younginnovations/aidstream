<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublishedToRegistryToActivityDataTable extends Migration
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
                $table->integer('published_to_registry')->default(0);
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
                $table->dropColumn('published_to_registry');
            }
        );
    }
}
