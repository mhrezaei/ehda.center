
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code_melli' , 20)->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('mobile')->nullable()->index();
            $table->string('name_first')->nullable();
            $table->string('name_last')->nullable();
            $table->string('name_firm')->nullable();
            $table->string('password')->nullable();
            $table->boolean('password_force_change')->default(0);
            $table->longText('meta')->nullable() ;
            $table->tinyInteger('gender')->default(0);
            $table->boolean('newsletter')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('created_by')->default(0)->index() ;
            $table->unsignedInteger('updated_by')->default(0) ;
            $table->unsignedInteger('deleted_by')->default(0) ;
            $table->unsignedInteger('published_by')->default(0) ;

            $table->index(['name_last','name_first']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
