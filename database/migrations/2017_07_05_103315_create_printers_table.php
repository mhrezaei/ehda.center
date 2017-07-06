<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreatePrintersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('printers', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->index();
			$table->unsignedInteger('printing_id')->index();
			$table->string('name_full') ;
			$table->string('name_father');
			$table->string('code_melli');
			$table->string('birth_date');
			$table->string('registered_at');
			$table->string('card_no');
			$table->string('from_domain');
			$table->string('status');

			$table->timestamps();


		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('printers');
	}
}
