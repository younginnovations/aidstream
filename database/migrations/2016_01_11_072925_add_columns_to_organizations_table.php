<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->string('twitter')->nullable();
            $table->bigInteger('disqus_comments')->nullable();
            $table->string('logo')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('organization_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('twitter');
            $table->dropColumn('disqus_comments');
            $table->dropColumn('logo');
            $table->dropColumn('logo_url');
            $table->dropColumn('organization_url');
        });
    }
}
