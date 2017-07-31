<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('country_id')->default(0)->index();
            $table->unsignedInteger('parent_id')->default(0)->index();
            $table->unsignedInteger('capital_id')->default(0)->index();
            $table->unsignedInteger('domain_id')->default(0)->index();
            $table->string('title')->index();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('states');
    }
}
