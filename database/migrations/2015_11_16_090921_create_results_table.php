<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'activity_results',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('activity_id');
                $table->json('result');
                $table->timestamps();

                $table->foreign('activity_id')->references('id')->on('activity_data')->onDelete('cascade');
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
        Schema::drop('activity_results');
    }
}
