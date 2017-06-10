<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateDomainsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('domains', function (Blueprint $table) {
			$table->increments('id');

			$table->string('slug',100)->index() ;
			$table->string('alias',100)->index() ;
			$table->string('title')->index() ;

			$table->longText('meta')->nullable() ;
			$table->timestamps();
			$table->softDeletes();
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
		Schema::dropIfExists('domains');
	}
}
