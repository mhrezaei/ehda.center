<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreatePacksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('packs', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('unit_id')->index() ;
			$table->string('type')->index() ;

			$table->string('slug') ;
			$table->string('title')->index();
			$table->text('text')->nullable() ;
			$table->integer('inventory')->default(0);
			$table->integer('inventory_alarm')->default(0);
			$table->integer('inventory_stop')->default(0);

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
		Schema::dropIfExists('packs');
	}
}
