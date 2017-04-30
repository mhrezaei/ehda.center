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
			$table->unsignedInteger('user_id')->after('id')->index();
			$table->string('type')->after('post_id')->index();
			$table->string('name')->after('ip') ;
			$table->string('email')->after('name')->index() ;
			$table->string('subject')->after('email') ;
			$table->longText('text')->after('subject');
			$table->boolean('is_private')->after('meta');
			$table->boolean('is_by_admin')->after('is_private');
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
			$table->dropColumn(['user_id','type','name','email','subject','text','is_private','is_by_admin']);
		});
	}
}
