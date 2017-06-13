<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadedFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('uploaded_files', function (Blueprint $table) {
            $table->increments('id');

            // Storage Info
            $table->string('original_name');
            $table->string('physical_name');
            $table->string('directory');

            // File Info
            $table->string('mime_type');
            $table->string('extension');
            $table->bigInteger('size'); // in bytes
            $table->longText('hash_file')->nullable();

            // File Status
            $table->integer('status');

            // Searching Tools
            $table->string('tags')->nullable();

            // Other Info
            $table->longText('meta')->nullable();

            // Times of Any Event Happening on an Uploaded File
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('moderated_at')->nullable();
            $table->index('created_at');

            // Functors of Any Event Happening on an Uploaded File
            $table->unsignedInteger('created_by')->default(0)->index() ;
            $table->unsignedInteger('updated_by')->default(0) ;
            $table->unsignedInteger('deleted_by')->default(0) ;
            $table->unsignedInteger('published_by')->default(0) ;
            $table->unsignedInteger('owned_by')->default(0)->index();
            $table->unsignedInteger('moderated_by')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploaded_files');
    }
}