<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders' , function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('posttype_id')->index() ;
            $table->unsignedInteger('parent_id')->default(0)->index() ;
            $table->string('locale' , 2)->default('fa')->index() ;
            $table->string('slug')->index() ;
            $table->string('title')->index();
            $table->longText('meta')->nullable() ;

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0)->index() ;
            $table->unsignedInteger('updated_by')->default(0) ;
            $table->unsignedInteger('deleted_by')->default(0) ;

            $table->foreign('posttype_id')->references('id')->on('posttypes')->onDelete('cascade');
        });
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
//            $table->unsignedInteger('posttype_id')->index() ;
//            $table->unsignedInteger('parent_id')->default(0)->index() ;
            $table->unsignedInteger('folder_id')->index() ;
            $table->string('slug')->index() ;
            $table->string('title')->index();
            $table->longText('meta')->nullable() ;

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0)->index() ;
            $table->unsignedInteger('updated_by')->default(0) ;
            $table->unsignedInteger('deleted_by')->default(0) ;

//            $table->foreign('posttype_id')->references('id')->on('posttypes')->onDelete('cascade');
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
        Schema::dropIfExists('categories');
        Schema::dropIfExists('folders');
    }
}
