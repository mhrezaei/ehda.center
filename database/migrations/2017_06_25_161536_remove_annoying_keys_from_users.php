<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class RemoveAnnoyingKeysFromUsers extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropForeign('users_domain_foreign');
			$table->dropForeign('users_from_domain_foreign');
			$table->dropForeign('users_edu_city_foreign');
			$table->dropForeign('users_birth_city_foreign');
			$table->dropForeign('users_home_province_foreign');
			$table->dropForeign('users_home_city_foreign');
			$table->dropForeign('users_work_province_foreign');
			$table->dropForeign('users_work_city_foreign');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//Schema::table('users', function (Blueprint $table) {
		//	$table->foreign('domain')
		//		->references('id')
		//		->on('domains')
		//		->onDelete('NO ACTION')
		//		->onUpdate('CASCADE')
		//	;
		//	$table->foreign('from_domain')
		//		->references('id')
		//		->on('domains')
		//		->onDelete('NO ACTION')
		//		->onUpdate('CASCADE')
		//	;
		//	$table->foreign('birth_city')
		//		->references('id')
		//		->on('states')
		//		->onDelete('NO ACTION')
		//		->onUpdate('CASCADE')
		//	;

		//});
	}
}
