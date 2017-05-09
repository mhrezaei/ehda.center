<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddSalePriceToPostsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('posts', function (Blueprint $table) {
			$table->string('title2')->after('title');
			$table->timestamp('sale_expires_at')->nullable()->after('price') ;
			$table->timestamp('sale_starts_at')->nullable()->after('price') ;
			$table->float('sale_price' , 15 , 2)->default(0)->after('price') ;
			$table->integer('seo_score')->default(-1);
			$table->timestamp('pinned_at')->nullable()->index() ;
			$table->unsignedInteger('pinned_by')->default(0) ;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('posts', function (Blueprint $table) {
			$table->dropColumn(['title2','sale_price','seo_score','sale_starts_at' , 'sale_expires_at' , 'pinned_at' , 'pinned_by']);
		});
	}
}
