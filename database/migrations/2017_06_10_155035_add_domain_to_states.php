<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddDomainToStates extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('states', function (Blueprint $table) {
			$table->unsignedInteger('domain_id')->after('capital_id')->default(0)->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('states', function (Blueprint $table) {
			$table->dropColumn([
				'domain_id',
			]);
		});
	}
}
