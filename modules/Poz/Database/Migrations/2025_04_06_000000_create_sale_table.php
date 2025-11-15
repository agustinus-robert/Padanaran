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
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        Schema::create('sale', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->foreignId('customer_id')->nullable()->constrained('customer')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('stdnts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('sale_status');
            $table->decimal('discount', 20, 2);
            $table->decimal('sub_total', 20, 2);
            $table->decimal('grand_total', 20, 2);
            $table->decimal('total_payment', 20, 2)->nullable();
            $table->integer('pos')->nullable();
            $table->timestampTz('sale_date')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sale')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('product')->cascadeOnUpdate()->cascadeOnDelete();
            //   $table->foreignId('outlet_id')->constrained('outlet')->cascadeOnUpdate()->cascadeOnDelete();

            $table->integer('qty');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('sale_direct', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('customer_name')->nullable();
            $table->string('desk_name')->nullable();
            $table->string('email')->nullable();
            $table->integer('sale_status');
            $table->decimal('subtotal', 20, 2)->nullable();
            $table->decimal('discount', 20, 2)->nullable();
            $table->decimal('total_payment', 20, 2)->nullable();
            $table->decimal('grand_total', 20, 2)->nullable();
            $table->longText('note')->nullable();
            $table->timestampTz('bill_at')->nullable();
            $table->string('purchase')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('sale_direct_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sale_direct')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('product')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('product_name');
            $table->integer('qty');
            $table->decimal('price', 20, 2);
            $table->text('location')->nullable();
            $table->text('image_name')->nullable();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('sale_direct_chart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('product')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('product_name', 225);
            $table->integer('qty');
            $table->decimal('price', 20, 2);
            $table->text('location');
            $table->text('image_name');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('sale_direct_customer_desk', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('desk_name');
            $table->string('email');

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
        Schema::dropIfExists('sale');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sale_direct');
        Schema::dropIfExists('sale_direct_items');
        Schema::dropIfExists('sale_direct_chart');
        Schema::dropIfExists('sale_direct_customer_desk');
    }
};
