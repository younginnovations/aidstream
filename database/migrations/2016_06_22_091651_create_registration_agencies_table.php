<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'registration_agencies',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('org_id');
                $table->string('country');
                $table->string('short_form');
                $table->string('name');
                $table->string('website')->nullable();
                $table->boolean('moderated');
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
        Schema::drop('registration_agencies');
    }
}
