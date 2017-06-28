<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateCartsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carts', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->index();
			$table->unsignedInteger('courier_id')->index();

			$table->string('tracking_no')->index();
			$table->float('total_main_price', 15, 2)->default(0);
			$table->float('total_sale_discount', 15, 2)->default(0);
			$table->float('total_courier_cost', 15, 2)->default(0);
			$table->float('coupon_discount', 15, 2)->default(0);
			$table->float('overhead_discount', 15, 2)->default(0);
			$table->float('applicable_tax', 15, 2)->default(0);
			$table->float('total_invoiced', 15, 2)->default(0);
			$table->float('total_paid', 15, 2)->default(0);

			$table->tinyInteger('status')->default(0);
			$table->string('courier_tracking_no')->index() ;

			$table->longText('meta')->nullable() ;
			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('created_by')->default(0)->index() ;
			$table->unsignedInteger('updated_by')->default(0) ;
			$table->unsignedInteger('deleted_by')->default(0) ;

            $table->boolean('converted')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('carts');
	}
}
