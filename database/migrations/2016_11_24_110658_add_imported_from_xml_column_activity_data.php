<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImportedFromXmlColumnActivityData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'activity_data',
            function (Blueprint $table) {
                $table->boolean('imported_from_xml')->nullable();
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
            'activity_data',
            function (Blueprint $table) {
                $table->dropColumn('imported_from_xml');
            }
        );
    }
}
