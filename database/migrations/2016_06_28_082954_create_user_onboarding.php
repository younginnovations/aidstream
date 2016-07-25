<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserOnboarding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'user_onboarding',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->boolean('has_logged_in_once')->default(0);
                $table->boolean('completed_tour')->default(0);
                $table->json('completed_steps')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('user_onboarding');
    }
}
