<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddMetaToSettings extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('settings', function (Blueprint $table) {
			$table->longText('meta')->nullable()->after('is_localized');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('settings', function (Blueprint $table) {
			$table->dropColumn([
				'meta',
			]);
		});
	}
}
