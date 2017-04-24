<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddTypeToComments extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->string('type')->after('id')->index();
			$table->string('name')->after('ip') ;
			$table->string('email')->after('name')->index() ;
			$table->string('subject')->after('email') ;
			$table->longText('text')->after('subject');
			$table->boolean('is_private')->after('meta');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->dropColumn(['type','name','email','subject','text','is_private']);
		});
	}
}
