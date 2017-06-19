<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFolderPostRelationTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('folder_post', function (Blueprint $table) {
			$table->increments('id');

			$table->unsignedInteger('folder_id')->index();
			$table->unsignedInteger('post_id')->index();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
			$table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('folder_post');
	}
}
