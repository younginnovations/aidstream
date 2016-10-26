<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegistrationAgencyAndNumberToOrganizationsTable extends Migration
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
                $table->string('registration_agency')->nullable();
                $table->string('registration_number')->nullable();
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
                $table->dropColumn('registration_agency');
                $table->dropColumn('registration_number');
            }
        );
    }
}
