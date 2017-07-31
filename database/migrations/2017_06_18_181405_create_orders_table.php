<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->index() ;
			$table->unsignedInteger('good_id')->index() ;
			$table->unsignedInteger('post_id')->index() ;
			$table->unsignedInteger('cart_id')->index() ;
			$table->string('type')->index() ;

			$table->float('price' , 15 , 2)->default(0) ;
			$table->float('sale_price' , 15 , 2)->default(0) ;
			$table->float('quantity' , 15 , 2)->default(1);
			$table->tinyInteger('is_sold')->default(0);

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
		Schema::dropIfExists('orders');
	}
}
