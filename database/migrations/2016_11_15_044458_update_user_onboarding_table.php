<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserOnboardingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'user_onboarding',
            function (Blueprint $table) {
                $table->dropColumn('dashboard_completed_steps');
                $table->boolean('display_hints')->default(1);
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
            'user_onboarding',
            function (Blueprint $table) {
                $table->json('dashboard_completed_steps')->nullable();
                $table->dropColumn('display_hints');
            }
        );
    }
}
