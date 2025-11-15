<?php

namespace Modules\Poz\Database\Seeders;

use Modules\Account\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sqlFiles = [
            'outlet.sql',
            'brand.sql', 'outlet_brand.sql',
            'category.sql', 'outlet_category.sql',
            'unit.sql', 'outlet_unit.sql',
            'tax_rate.sql', 'outlet_tax_rate.sql',
            'product.sql', 'outlet_product.sql',
            'user_casier_outlet.sql', 'user_tokens.sql'
        ];

        DB::statement("SET session_replication_role = 'replica';");

        foreach ($sqlFiles as $file) {
            $path = base_path("modules/Poz/Database/Seeders/sql/{$file}");
            if (File::exists($path)) {
                $sql = File::get($path);
                DB::unprepared($sql);
            } else {
                echo "File tidak ditemukan: {$file}\n";
            }
        }

        DB::statement("SET session_replication_role = 'origin';");

        echo "Import SQL selesai.\n";
    }
}
