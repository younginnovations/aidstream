<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'activity_location',
            function(Blueprint $table){
                $table->increments('id');
                $table->integer('activity_id');
                $table->integer('municipality_id');
                $table->integer('ward')->nullable();
                $table->timestamps();

                $table->foreign('activity_id')->references('id')->on('activity_data')->onDelete('cascade');
                $table->foreign('municipality_id')->references('id')->on('municipalities');
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
        Schema::drop('activity_location');
    }
}
