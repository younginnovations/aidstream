<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('organization_data', function(Blueprint $table)
		{
			$table->increments('id');
			$table->json('name');
			$table->json('total_budget');
			$table->json('recipient_organization_budget');
			$table->json('recipient_country_budget');
			$table->json('document_link');
			$table->integer('organization_id');
			$table->timestamps();

			$table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('organization_data');
	}

}
