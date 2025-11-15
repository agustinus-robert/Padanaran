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
        Schema::create('purchase', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->foreignId('supplier_id')->constrained('supplier')->cascadeOnUpdate()->cascadeOnDelete();
           // $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete(); // Ditambahkan outlet
            $table->integer('is_pos')->nullable();
            $table->integer('purchase_status');
            $table->decimal('discount', 20, 2);
            $table->decimal('grand_total', 20, 2);
            $table->timestampTz('purchase_date');
            $table->timestampTz('purchase_delivered')->nullable();
            $table->timestampTz('purchase_completed')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchase')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('product')->cascadeOnUpdate()->cascadeOnDelete();
           // $table->foreignId('warehouse_id')->constrained('warehouse')->cascadeOnUpdate()->cascadeOnDelete();
            // $table->foreignId('transfer_id')->nullable()->constrained('transfer')->nullOnDelete();
          //  $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete(); // Ditambahkan outlet

            $table->integer('qty');

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
        Schema::dropIfExists('purchase');
        Schema::dropIfExists('purchase_items');
    }
};
