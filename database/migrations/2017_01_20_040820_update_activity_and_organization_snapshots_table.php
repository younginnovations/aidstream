<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateActivityAndOrganizationSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_snapshots', function (Blueprint $table) {
            $table->dropForeign('activity_snapshots_activity_id_foreign');
            $table->dropForeign('activity_snapshots_org_id_foreign');
            $table->foreign('activity_id')->references('id')->on('activity_data')->onDelete('cascade');
            $table->foreign('org_id')->references('id')->on('organizations')->onDelete('cascade');
        });

        Schema::table('organization_snapshots', function (Blueprint $table) {
            $table->dropForeign('organization_snapshots_org_id_foreign');
            $table->foreign('org_id')->references('id')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_snapshots', function (Blueprint $table) {
            $table->dropForeign('organization_snapshots_org_id_foreign');
            $table->foreign('org_id')->references('id')->on('organizations');
        });

        Schema::table('activity_snapshots', function (Blueprint $table) {
            $table->dropForeign('activity_snapshots_activity_id_foreign');
            $table->dropForeign('activity_snapshots_org_id_foreign');
            $table->foreign('activity_id')->references('id')->on('activity_data');
            $table->foreign('org_id')->references('id')->on('organizations');
        });
    }
}
