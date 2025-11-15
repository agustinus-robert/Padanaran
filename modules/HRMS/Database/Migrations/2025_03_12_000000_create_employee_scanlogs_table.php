<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empl_scan_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedTinyInteger('location');
            $table->string('latlong')->nullable();
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_teacher_scan_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedTinyInteger('location');
            $table->string('room')->nullable();
            $table->string('latlong')->nullable();
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('empl_scan_logs');
        Schema::drop('empl_teacher_scan_logs');
    }
};
