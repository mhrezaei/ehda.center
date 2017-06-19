<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateUserDrawingTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('drawings', function (Blueprint $table) {
			$table->unsignedInteger('user_id')->index() ;
			$table->unsignedInteger('post_id')->index() ;
			$table->bigInteger('amount') ;
			$table->integer('lower_line') ;
			$table->integer('upper_line') ;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('drawings');
	}
}
