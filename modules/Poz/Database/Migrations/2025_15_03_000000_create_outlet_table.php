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
        Schema::create('outlet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('code', 10);
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('image_name')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('user_casier_outlet', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('outlet_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('product')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('outlet_brand', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained('brand')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('outlet_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('category')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('outlet_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('unit')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('outlet_tax_rate', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('tax_id')->constrained('tax_rate')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('outlet_sale', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained('sale')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('outlet_purchase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('purchase_id')->constrained('purchase')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('outlet_return', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('return_id')->constrained('return')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('outlet_supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('supplier')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('outlet_product_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_stock_id')->constrained('product_stock')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('outlet_product_adjustment', function (Blueprint $table){
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('adjustment_id')->constrained('product_adjustment')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

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
        Schema::dropIfExists('outlet');
        Schema::dropIfExists('user_casier_outlet');
    }
};
