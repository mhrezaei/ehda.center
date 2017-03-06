<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('plural_title');
            $table->longText('modules')->nullable();
            $table->longText('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index() ;
            $table->unsignedInteger('role_id')->index() ;
            $table->longText('permissions')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');;
        });

//        Schema::create('post_role', function (Blueprint $table) {
//            $table->increments('id');
//            $table->unsignedInteger('role_id')->index() ;
//            $table->unsignedInteger('post_id')->index() ;
//            $table->timestamps();
//
//            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
//            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
//        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('post_role');
        Schema::dropIfExists('roles');

    }
}
