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
        Schema::create('cash_register', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casier_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('money', 20, 2);
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('cash_register_topup', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casier_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('money', 20, 2);
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('cash_register_log', function(Blueprint $table){
            $table->id();
            $table->foreignId('cash_register_id')->constrained('cash_register')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('status', ['plus', 'minus']);
            $table->decimal('money', 20, 2);
            $table->softDeletesTz();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('desk');
    }
};
