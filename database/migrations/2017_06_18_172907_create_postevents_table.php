<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosteventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postevents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('post_id')->index();
            $table->string('description');
            $table->longText('footprint');

            $table->timestamps();
            $table->unsignedInteger('created_by')->default(0)->index();
            $table->unsignedInteger('updated_by')->default(0);

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
    }
}
