<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityPublishedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'activity_published',
            function (Blueprint $table) {
                $table->increments('id');
                $table->json('published_activities')->nullable();
                $table->string('filename');
                $table->integer('published_to_register')->default(0);
                $table->integer('organization_id');
                $table->timestamps();

                $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
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
        Schema::drop('activity_published');
    }
}
