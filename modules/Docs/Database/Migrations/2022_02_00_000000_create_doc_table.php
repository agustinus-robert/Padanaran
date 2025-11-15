<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('docs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kd')->unique();
            $table->tinyInteger('type')->default(1); // unsignedTinyInteger → tinyInteger
            $table->string('qr')->nullable()->unique();
            $table->string('label')->nullable();
            $table->string('path')->nullable();
            $table->nullableMorphs('modelable'); // Will create: modelable_type (string) and modelable_id (bigint)
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('doc_signatures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('qr')->nullable()->unique();
            $table->bigInteger('doc_id'); // unsignedInteger → bigInteger
            $table->bigInteger('user_id');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('doc_id')->references('id')->on('docs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doc_signatures');
        Schema::dropIfExists('docs');
    }
};
