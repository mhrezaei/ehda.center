<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddManagerFieldToRolesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('roles', function (Blueprint $table) {
			$table->tinyInteger('is_manager')->after('modules')->index() ;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('roles', function (Blueprint $table) {
			$table->dropColumn([
				'is_manager',
			]);
		});
	}
}
