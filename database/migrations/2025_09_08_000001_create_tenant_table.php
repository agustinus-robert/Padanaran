<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('central_tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');                   
            $table->string('email');                   
            $table->string('domain')->unique();    
            $table->string('database');                         
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('central_tenants_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('central_tenants')->cascadeOnDelete();
            $table->string('key');         
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_tenants');
        Schema::dropIfExists('central_tenants_meta');
    }
};
