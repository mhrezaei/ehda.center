<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_posts', function (Blueprint $table) {
            // Primary Key
            $table->increments('id');

            $table->unsignedInteger('post_id')->index();
            $table->unsignedInteger('order_id')->index();

            $table->integer('count')->default(1)->index();
            $table->float('original_price', 15, 2)->index();
            $table->float('offered_price', 15, 2)->index();
            $table->float('total_price', 15, 2)->index();

            $table->longText('details')->nullable();

            // Meta
            $table->longText('meta')->nullable();

            // Activity Logs
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0)->index();
            $table->unsignedInteger('updated_by')->default(0);
            $table->unsignedInteger('deleted_by')->default(0);

            // Special
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
        Schema::dropIfExists('order_posts');
    }
}
