<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOrgTypeAndOrgCountryAndFlagOrganizationsData extends Migration
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
                $table->string('type')->nullable();
                $table->string('country')->nullable();
                $table->string('identifier')->nullable();
                $table->boolean('is_reporting_org')->default(true);
                $table->boolean('is_publisher')->default(false)->nullable();
                $table->json('used_by')->default(json_encode([]));
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
                $table->dropColumn('type');
                $table->dropColumn('country');
                $table->dropColumn('identifier');
                $table->dropColumn('is_reporting_org');
                $table->dropColumn('is_publisher');
                $table->dropColumn('used_by');
            }
        );
    }
}
