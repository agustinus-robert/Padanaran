<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Traits\Metable\MetableSchema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_form', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('title', 255);
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('post_form_builder', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_form_id')->constrained('post_form')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('title');
            $table->string('type');

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('post_form_builder_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_form_id')->constrained('post_form')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('post_form_builder_id')->constrained('post_form_builder')->cascadeOnUpdate()->cascadeOnDelete();
            $table->jsonb('data');

            $table->softDeletesTz();
            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_form');
        Schema::dropIfExists('post_form_builder');
        Schema::dropIfExists('post_form_builder_data');
    }
};
