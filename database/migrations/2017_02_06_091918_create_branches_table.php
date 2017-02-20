<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posttypes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique() ;
            $table->tinyInteger('order')->default(1);
            $table->string('title');
            $table->text('features');
            $table->string('header_title'); //necessary for grouping of menus
            $table->longText('meta')->nullable() ;
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order','title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posttypes');
    }
}
