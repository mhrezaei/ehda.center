<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->string('amount_payable', 50);
            $table->string('amount_paid', 50)->default(0);
            $table->string('tracking_number', 50)->index();
            $table->string('authority_code', 50)->index()->nullable();
            $table->string('ref_id', 50)->index()->nullable();
            $table->integer('status')->default(0);
            $table->integer('payment_status')->default(0);
            $table->text('redirect_url');
            $table->longText('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
