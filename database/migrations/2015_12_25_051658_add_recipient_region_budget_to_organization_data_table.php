<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecipientRegionBudgetToOrganizationDataTable extends Migration
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
                $table->json('recipient_region_budget')->nullable();
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
                $table->dropColumn('recipient_region_budget');
            }
        );
    }
}
