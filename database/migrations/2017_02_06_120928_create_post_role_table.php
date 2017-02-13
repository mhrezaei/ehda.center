<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_role', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id')->index() ;
            $table->unsignedInteger('post_id')->index() ;
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('post_id')->references('id')->on('posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_role');
    }
}
