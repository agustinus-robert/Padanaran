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
        Schema::create('empl_schedule_duty_shift_teachers', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->time('start_at');
            $table->time('end_at');
            $table->integer('status');
            $table->unsignedSmallInteger('grade_id');
            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');


            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down() {}
};