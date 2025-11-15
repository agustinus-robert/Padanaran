<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_tax_rates', function (Blueprint $table) {
            $table->id(); // Menggunakan 'id()' untuk auto increment id di PostgreSQL
            $table->string('category', 5);
            $table->decimal('lower_bound', 15, 2); // PostgreSQL mendukung tipe 'decimal' dengan presisi yang lebih tinggi
            $table->decimal('upper_bound', 15, 2)->nullable(); // Menambahkan nullable untuk upper_bound
            $table->decimal('rate', 5, 2); // Menyimpan rate dengan presisi
            $table->softDeletes();
            $table->timestamps(0); // Menyimpan timestamp dengan precision 0 detik di PostgreSQL
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_tax_rates');
    }
};
