<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecondaryContactToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'organizations',
            function (Blueprint $table) {
                $table->json('secondary_contact')->nullable();
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
            'organizations',
            function (Blueprint $table) {
                $table->dropColumn('secondary_contact');
            }
        );
    }
}
