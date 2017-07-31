<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateCouriersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('couriers', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('province_id') ;
			$table->unsignedInteger('city_id');

			$table->string('title')->index() ;
			$table->float('price' , 15 , 2)->default(0) ;
			$table->float('min_for_free' , 15 , 2)->default(0);

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
		Schema::dropIfExists('couriers');
	}
}
