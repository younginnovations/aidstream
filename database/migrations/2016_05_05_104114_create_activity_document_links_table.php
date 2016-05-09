<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityDocumentLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'activity_document_links',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('activity_id');
                $table->json('document_link');
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
        Schema::drop('activity_document_links');
    }
}
