<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateGoodsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('goods', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('unit_id')->index() ;
			$table->unsignedInteger('pack_id')->index() ;
			$table->string('type')->index() ;
			$table->string('sisterhood')->index() ;
			$table->string('color')->index() ;

			$table->string('title')->index();
			$table->text('text')->nullable() ;
			$table->text('locales')->nullable() ;
			$table->integer('order')->default(0) ;
			$table->integer('inventory')->default(0);
			$table->integer('inventory_alarm')->default(0);
			$table->integer('inventory_stop')->default(0);

			$table->float('price' , 15 , 2)->default(0) ;
			$table->float('sale_price' , 15 , 2)->default(0) ;
			$table->timestamp('sale_expires_at')->nullable() ;

			$table->longText('meta')->nullable() ;
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('published_at')->nullable();
			$table->unsignedInteger('created_by')->default(0)->index() ;
			$table->unsignedInteger('updated_by')->default(0) ;
			$table->unsignedInteger('deleted_by')->default(0) ;

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('goods');
	}
}
