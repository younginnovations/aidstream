<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'organization_location',
            function(Blueprint $table){
                $table->increments('id');
                $table->integer('organization_id');
                $table->integer('district_id');
                $table->integer('municipality_id')->nullable();
                $table->timestamps();

                $table->foreign('organizations')->references('id')->on('organization')->onDelete('cascade');
                $table->foreign('municipality_id')->references('id')->on('municipalities');
                $table->foreign('district_id')->references('id')->on('districts');
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
