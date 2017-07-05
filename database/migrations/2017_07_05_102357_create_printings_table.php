<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreatePrintingsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('printings', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->index() ;
			$table->unsignedInteger('event_id')->index() ;
			$table->string('domain',100)->index();

			$table->timestamp('queued_at')->nullable() ;
			$table->timestamp('printed_at')->nullable() ;
			$table->timestamp('verified_at')->nullable() ;
			$table->timestamp('dispatched_at')->nullable() ;
			$table->timestamp('delivered_at')->nullable() ;

			$table->unsignedInteger('queued_by')->default(0)->index() ;
			$table->unsignedInteger('printed_by')->default(0)->index() ;
			$table->unsignedInteger('verified_by')->default(0)->index() ;
			$table->unsignedInteger('dispatched_by')->default(0)->index() ;
			$table->unsignedInteger('delivered_by')->default(0)->index() ;

			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('created_by')->default(0)->index();
			$table->unsignedInteger('updated_by')->default(0);
			$table->unsignedInteger('deleted_by')->default(0);

			$table->unsignedInteger('converted')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('printings');
	}
}
