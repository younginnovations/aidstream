<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationSnapshotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'organization_snapshots',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('org_id')->unsigned();
                $table->foreign('org_id')->references('id')->on('organizations');
                $table->json('transaction_totals');
                $table->boolean('published_to_registry');
                $table->string('org_slug');
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
        Schema::drop('organization_snapshots');
    }
}
