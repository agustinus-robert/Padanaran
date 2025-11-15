<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Traits\Metable\MetableSchema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empl_schedules_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->date('start_at');
            $table->date('end_at');
            $table->text('dates')->nullable();
            $table->unsignedTinyInteger('workdays_count')->default(0);
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_schedule_submissions_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->date('start_at');
            $table->date('end_at');
            $table->text('dates')->nullable();
            $table->unsignedTinyInteger('workdays_count')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_schedule_duty_teachers', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
           // $table->unsignedSmallInteger('room_id');
            $table->date('start_at');
            $table->date('end_at');
            $table->text('dates')->nullable();
            $table->unsignedTinyInteger('workdays_count')->default(0);
            $table->integer('is_active')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

           // $table->foreign('room_id')->references('id')->on('sch_building_rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};
