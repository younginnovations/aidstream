<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublishedOrgDataOrganizationsPublishedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'organization_published',
            function (Blueprint $table) {
                $table->json('published_org_data')->nullable();
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
            'organization_published',
            function (Blueprint $table) {
                $table->dropColumn('published_org_data');
            }
        );
    }
}
