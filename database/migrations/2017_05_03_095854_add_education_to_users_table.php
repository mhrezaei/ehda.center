<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddEducationToUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->date('marriage_date')->after('gender')->index() ;
			$table->date('birth_date')->after('gender')->index() ;
			$table->tinyInteger('education')->after('gender')->index();
			$table->tinyInteger('marital')->after('gender')->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn(['marriage_date','birth_date','education','marital']);
		});
	}
}
