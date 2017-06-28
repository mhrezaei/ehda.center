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
            // Primary Key
            $table->increments('id');
            
            // Card Info
            $table->unsignedBigInteger('card_no');

            // Personal Info
            $table->string('code_melli', 20)->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('name_first')->nullable()->index();
            $table->string('name_last')->nullable()->index();
            $table->string('name_father')->nullable();
            $table->string('name_firm')->nullable();
            $table->string('code_id')->nullable();
            $table->unsignedInteger('card_id');
            $table->tinyInteger('gender')->default(0);

            // Birth Info
            $table->date('birth_date')->nullable()->index();
            $table->unsignedInteger('birth_city')->nullable()->index();
            $table->foreign('birth_city')
                ->references('id')
                ->on('states')
                ->onDelete('NO ACTION')
                ->onUpdate('CASCADE');

            // Marriage Info
            $table->tinyInteger('marital')->index();
            $table->date('marriage_date')->nullable()->index();

            // Roles Registered Time
            $table->timestamp('volunteer_registered_at')->nullable();
            $table->timestamp('card_registered_at')->nullable();

            // Contact Info
            $table->string('mobile')->nullable()->index();
            $table->string('tel_emergency')->nullable()->index();
            $table->longText('home_address')->nullable();
            $table->unsignedInteger('home_province')->nullable();
            $table->foreign('home_province')
                ->references('id')
                ->on('states')
                ->onDelete('NO ACTION')
                ->onUpdate('CASCADE');
            $table->unsignedInteger('home_city')->nullable();
            $table->foreign('home_city')
                ->references('id')
                ->on('states')
                ->onDelete('NO ACTION')
                ->onUpdate('CASCADE');
            $table->string('home_tel')->nullable();
            $table->string('home_postal')->nullable();
            $table->longText('work_address')->nullable();
            $table->unsignedInteger('work_province')->nullable();
            $table->foreign('work_province')
                ->references('id')
                ->on('states')
                ->onDelete('NO ACTION')
                ->onUpdate('CASCADE');
            $table->unsignedInteger('work_city')->nullable();
            $table->foreign('work_city')
                ->references('id')
                ->on('states')
                ->onDelete('NO ACTION')
                ->onUpdate('CASCADE');
            $table->string('work_tel')->nullable();
            $table->string('work_postal')->nullable();

            // Education Info
            $table->tinyInteger('edu_level')->nullable();
            $table->unsignedInteger('edu_city')->nullable();
            $table->foreign('edu_city')
                ->references('id')
                ->on('states')
                ->onDelete('NO ACTION')
                ->onUpdate('CASCADE');
            $table->string('edu_field')->nullable();

            // Job Info
            $table->string('job')->nullable();

            // Password
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->string('reset_token')->nullable();
            $table->boolean('password_force_change')->default(0);

            // Meta
            $table->longText('meta')->nullable();
            $table->tinyInteger('status')->default(0);

            // Temporary
            $table->longText('unverified_changes')->nullable();
            $table->tinyInteger('unverified_flag')->nullable();

            // Exam
            $table->timestamp('exam_passed_at')->nullable();
            $table->longText('exam_sheet')->nullable();
            $table->integer('exam_result')->nullalbe();

            // Familiarization
            $table->tinyInteger('familiarization')->nullable();
            $table->string('motivation')->nullable();

            // Activation
            $table->string('alloc_time')->nullable();
            $table->string('activities')->nullable();

            // Domain
            $table->string('domain')->nullable();
            $table->foreign('domain')
                ->references('slug') // @TODO: to be explained
                ->on('domains')
                ->onDelete('NO ACTION')
                ->onUpdate('CASCADE');
            $table->unsignedInteger('from_domain')->nullable();
            $table->foreign('from_domain')
                ->references('id')
                ->on('domains')
                ->onDelete('NO ACTION')
                ->onUpdate('CASCADE');

            // NewsLetter
            $table->boolean('newsletter')->default(0);

            // Event
            $table->unsignedInteger('from_event_id');

            // Activity Logs
            $table->timestamps();
            $table->index('created_at');
            $table->softDeletes();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('created_by')->default(0)->index();
            $table->unsignedInteger('updated_by')->default(0);
            $table->unsignedInteger('deleted_by')->default(0);
            $table->unsignedInteger('published_by')->default(0);

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
        Schema::dropIfExists('users');
    }
}
