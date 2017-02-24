<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('packages' , function (Blueprint $table) {
			$table->increments('id');
			$table->string('slug')->unique() ;
			$table->string('title')->index();
			$table->boolean('is_continuous')->default(0);

			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('created_by')->default(0)->index() ;
			$table->unsignedInteger('updated_by')->default(0) ;
			$table->unsignedInteger('deleted_by')->default(0) ;

		});
		Schema::create('posts', function (Blueprint $table) {
			//@TODO: Permissions

			$table->increments('id');
			$table->unsignedInteger('parent_id')->default(0)->index() ;
			$table->string('slug')->unique() ;
			$table->string('type')->index() ;
			$table->string('title')->index();
			$table->string('locale' , 2)->index() ;
			$table->float('price' , 15 , 2) ;
			$table->boolean('is_available')->default(1);
			$table->boolean('is_draft')->default(1);
			$table->boolean('is_limited')->default(0);

			$table->longText('meta')->nullable() ;

			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('published_at')->nullable();
			$table->unsignedInteger('created_by')->default(0)->index() ;
			$table->unsignedInteger('updated_by')->default(0) ;
			$table->unsignedInteger('deleted_by')->default(0) ;
			$table->unsignedInteger('published_by')->default(0) ;

			$table->unsignedInteger('owned_by')->default(0)->index();
			$table->unsignedInteger('moderated_by')->default(0)->index();
			$table->timestamp('moderated_at')->nullable();

			$table->index('created_at');
		});

		Schema::create('postevents' , function( Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('post_id')->index();
			$table->string('description') ;
			$table->longText('footprint');

			$table->timestamps();
			$table->unsignedInteger('created_by')->default(0)->index() ;
			$table->unsignedInteger('updated_by')->default(0) ;

			$table->index('created_at');
			$table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('postevents');
		Schema::dropIfExists('posts');
		Schema::dropIfExists('packages');
	}
}
