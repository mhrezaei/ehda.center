<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_downloads', function (Blueprint $table) {
            // Primary Key
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable()->index();

            $table->unsignedInteger('file_id')->index();
            $table->unsignedInteger('order_id')->index();

            $table->dateTime('expire_date')->nullable();

            $table->integer('downloadable_count')->index();
            $table->integer('downloaded_count')->default(0)->index();

            // Meta
            $table->longText('meta')->nullable() ;

            // Activity Logs
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0)->index() ;
            $table->unsignedInteger('updated_by')->default(0) ;
            $table->unsignedInteger('deleted_by')->default(0) ;

            // Special
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
        Schema::dropIfExists('file_downloads');
    }
}
