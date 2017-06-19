<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            // Primary Key
            $table->increments('id');

            // User
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('NO ACTION')
                ->onUpdate('CASCADE');

            // Receipts Info
            $table->string('code')->index();
            $table->timestamp('purchased_at')->nullable();
            $table->integer('purchased_amount');
            $table->string('operation_string')->nullable();
            $table->integer('operation_integer');
            $table->tinyInteger('is_verified');
            $table->longText('meta')->nullable();

            // Activity Logs
            $table->timestamps();
            $table->index('created_at');
            $table->softDeletes();
            $table->unsignedInteger('created_by')->default(0)->index();
            $table->unsignedInteger('updated_by')->default(0);
            $table->unsignedInteger('deleted_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
