<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoldersTable extends Migration
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
        Schema::dropIfExists('folders');
    }
}
