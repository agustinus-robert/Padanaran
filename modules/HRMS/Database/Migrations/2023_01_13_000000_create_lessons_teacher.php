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
        Schema::create('empl_schedule_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedSmallInteger('grade_id');
            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('empl_schedules_lesson', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_schedule_id');
            $table->string('name');
            $table->time('in');
            $table->time('out');
            $table->timestamps();

            $table->foreign('category_schedule_id')
                ->references('id')
                ->on('empl_schedule_category')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empl_schedules_lesson');
        Schema::dropIfExists('empl_schedule_category');
    }
};
