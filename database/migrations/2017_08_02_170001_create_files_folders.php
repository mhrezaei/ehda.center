<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesFolders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_folders', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('slug')->nullable();
            $table->integer('parent')->nullable();
            $table->string('persian_name')->nullable();
            $table->string('english_name')->nullable();

            // File Status
            $table->integer('status');

            // Times of Any Event Happening on an Uploaded File
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('moderated_at')->nullable();
            $table->index('created_at');

            // Functors of Any Event Happening on an Uploaded File
            $table->unsignedInteger('created_by')->default(0)->index();
            $table->unsignedInteger('updated_by')->default(0);
            $table->unsignedInteger('deleted_by')->default(0);
            $table->unsignedInteger('published_by')->default(0);
            $table->unsignedInteger('owned_by')->default(0)->index();
            $table->unsignedInteger('moderated_by')->default(0)->index();

            $table->boolean('converted')->default(0);
        });
        DB::table('files_folders')->insert([
            [
                'slug'         => "posts",
                'persian_name' => "پست‌ها",
                'english_name' => "Posts",
                'created_at'   => \Carbon\Carbon::now()->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files_folders');
    }
}
