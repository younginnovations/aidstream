<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('username')->unique();
                $table->string('password', 60);
                $table->integer('role_id')->nullable();
                $table->integer('org_id')->nullable();
                $table->json('user_permission')->nullable();
                $table->integer('time_zone_id')->default(1);
                $table->string('time_zone')->default('GMT');
                $table->rememberToken();
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
        Schema::drop('users');
    }

}
