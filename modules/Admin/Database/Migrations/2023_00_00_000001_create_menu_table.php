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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('icon', 50);
            $table->string('slug', 255);
            $table->integer('type');
            $table->jsonb('meta');
            $table->string('custom_links', 255);
            $table->jsonb('post_code');
            $table->jsonb('taxonomy_code');
            $table->jsonb('image_code');
            $table->jsonb('woocomerce_code');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('menu_has_role', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->jsonb('json_menu');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('menu_order', function (Blueprint $table) {
            $table->id();
            $table->jsonb('menu_text');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('menu_related', function (Blueprint $table) {
            $table->foreignId('from_menu')->constrained('menu')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('with_menu')->constrained('menu')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletesTz();
            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu');
        Schema::dropIfExists('menu_has_role');
        Schema::dropIfExists('menu_order');
        Schema::dropIfExists('menu_related');
    }
};
