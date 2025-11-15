<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\CompanyContract;
use Illuminate\Database\Seeder;

class ContractDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [4, 5];
        $contracts = [
            'honor' => 'SK Honorer',
            'tetap' => 'SK Tetap'
        ];

        foreach ($grades as $grade) {
            foreach ($contracts as $kd => $name) {
                CompanyContract::create([
                    'kd'    => $kd.'-'.$grade,
                    'name'  => $name,
                    'grade_id' => $grade,
                ]);
            }
        }
    }
}
