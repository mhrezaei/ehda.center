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
        Schema::create('posts', function (Blueprint $table) {
            //@TODO: Categories, Permissions

            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0)->index() ;
            $table->string('slug')->unique() ;
            $table->string('title')->index();
            $table->string('locale' , 2)->index() ;
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

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
