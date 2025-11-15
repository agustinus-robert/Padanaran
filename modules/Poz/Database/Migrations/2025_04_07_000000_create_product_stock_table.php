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
        Schema::create('product_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('product')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('supplier')->onDelete('cascade');
            $table->nullableMorphs('stockable');
            $table->enum('status', ['plus', 'minus']);
            $table->integer('qty');
            $table->decimal('grand_total', 20, 2)->nullable();
            $table->string('shift')->nullable();
            $table->tinyInteger('is_not_stock')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('wholesale', 20, 2)->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('pricesale', 20, 2)->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_stock');
    }
};
