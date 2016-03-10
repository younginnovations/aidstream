<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'organizations',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('user_identifier')->unique();
                $table->string('name');
                $table->string('address');
                $table->string('telephone')->nullable();
                $table->json('reporting_org')->nullable();
                $table->string('country')->nullable();
                $table->string('twitter')->nullable();
                $table->bigInteger('disqus_comments')->nullable();
                $table->string('logo')->nullable();
                $table->string('logo_url')->nullable();
                $table->string('organization_url')->nullable();
                $table->integer('status')->default(1);
                $table->integer('published_to_registry')->default(0);
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
        Schema::drop('organizations');
    }

}
