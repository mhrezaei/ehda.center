<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            // Primary Key
            $table->increments('id');

            // Personal Info
            $table->unsignedInteger('user_id')->nullable()->index() ;
            $table->string('code_melli', 20)->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('mobile')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->longText('address')->nullable();
            $table->string('postal_code')->nullable();

            // Status
            $table->tinyInteger('status')->nullable();

            // Financial Info
            $table->float('invoice_amount', 15, 2)->index();
            $table->float('payable_amount', 15, 2)->index();
            $table->float('paid_amount', 15, 2)->index();
            $table->float('discount_amount', 15, 2)->default(0);
            $table->float('tax_amount', 15, 2)->default(0);

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
        Schema::dropIfExists('orders');
    }
}
