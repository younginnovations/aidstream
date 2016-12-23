<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitySnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'activity_snapshots',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('org_id')->unsigned();
                $table->foreign('org_id')->references('id')->on('organizations');
                $table->integer('activity_id')->unsigned();
                $table->foreign('activity_id')->references('id')->on('activity_data');
                $table->json('published_data');
                $table->boolean('activity_in_registry');
                $table->string('filename');
                $table->timestamps();
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
        Schema::drop('activity_snapshots');
    }
}
