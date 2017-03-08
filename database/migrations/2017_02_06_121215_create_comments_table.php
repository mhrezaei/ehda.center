<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
//            $table->unsignedInteger('user_id')->default(0)->index();
            $table->unsignedInteger('post_id')->index();
            $table->unsignedInteger('replied_on')->index();

            $table->longText('meta')->nullable() ;

            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('created_by')->default(0)->index() ;
            $table->unsignedInteger('updated_by')->default(0) ;
            $table->unsignedInteger('deleted_by')->default(0) ;
            $table->unsignedInteger('published_by')->default(0) ;

            $table->index('created_at');
            $table->index('published_at');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');;

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
