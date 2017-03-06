<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique() ;
            $table->string('title');
            $table->string('category') ;
            $table->string('data_type');
//            $table->longText('meta')->nullable() ;
            $table->longText('default_value')->nullable() ;
            $table->longText('custom_value')->nullable();
            $table->boolean('developers_only')->default(0);
            $table->boolean('is_resident')->default(0);
            $table->boolean('is_localized')->default(0);
//            $table->boolean('is_sensitive')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
