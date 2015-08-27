<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('organizations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('identifier');
			$table->json('name');
			$table->string('address');
			$table->json('reporting_org')->nullable();
			$table->json('total_budget')->nullable();
			$table->json('recipient_org_budget')->nullable();
			$table->json('recipient_country_budget')->nullable();
			$table->json('document_link')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('organizations');
	}

}
