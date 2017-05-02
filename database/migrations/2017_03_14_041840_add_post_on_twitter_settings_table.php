<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostOnTwitterSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'settings',
            function (Blueprint $table) {
                $table->boolean('post_on_twitter')->default(1);
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
            'settings',
            function (Blueprint $table) {
                $table->dropColumn('post_on_twitter');
            }
        );
    }
}
