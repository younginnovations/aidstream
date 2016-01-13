<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'documents',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('filename')->nullable();
                $table->string('url')->nullable();
                $table->json('activities')->nullable();
                $table->integer('org_id');
                $table->timestamps();

                $table->foreign('org_id')->references('id')->on('organizations')->onDelete('cascade');
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
        Schema::drop('documents');
    }
}
