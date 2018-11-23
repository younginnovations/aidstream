<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrgDataToOrganizationDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'organization_data',
            function (Blueprint $table) {
                $table->json('org_data')->nullable();
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
        Schema::table(
            'organization_data',
            function (Blueprint $table) {
                $table->dropColumn('org_data');
            }
        );
    }
}
