<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'activity_transactions',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('activity_id');
                $table->foreign('activity_id')->references('id')->on('activity_data')->onDelete('cascade');
                $table->json('transaction');
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
        Schema::drop('activity_transactions');
    }
}
