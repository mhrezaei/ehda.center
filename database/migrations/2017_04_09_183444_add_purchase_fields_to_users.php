<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddPurchaseFieldsToUsers extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->unsignedBigInteger('total_receipts_count')->index() ;
			$table->unsignedBigInteger('total_receipts_amount')->index() ;
			$table->unsignedBigInteger('total_online_orders_count')->index() ;
			$table->unsignedBigInteger('total_online_orders_amount')->index();
			//
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
			$table->dropColumn([
				'total_receipts_count',
				'total_receipts_amount',
				'total_online_orders_count',
			     'total_online_orders_amount' ,
			]);
		});
	}
}
