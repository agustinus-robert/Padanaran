<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\CompanyVacationCategory;
use Illuminate\Database\Seeder;

class VacationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [4, 5];

        $vacation_categories = [
            [
                'name' => 'Cuti Tahunan (12 hari)',
                'type' => 1,
                'meta' => [
                    'quota'          => 12,
                    'fields'         => 'options',
                    'cashable'       => true,
                    'cashable_limit' => 5,
                ],
            ],
            [
                'name' => 'Cuti Bersalin',
                'type' => 2,
                'meta' => [
                    'quota'  => 90,
                    'fields' => 'range',
                ],
            ],
            [
                'name' => 'Cuti Gugur Kandungan',
                'type' => 2,
                'meta' => [
                    'quota'  => 45,
                    'fields' => 'range',
                ],
            ],
            [
                'name' => 'Libur Hari Raya',
                'type' => 3,
                'meta' => [
                    'quota'       => 7,
                    'fields'      => 'options',
                    'as_freelance' => true,
                ],
            ],
        ];

        foreach ($grades as $grade_id) {
            foreach ($vacation_categories as $ctg) {
                CompanyVacationCategory::create([
                    'name'     => $ctg['name'],
                    'type'     => $ctg['type'],
                    'meta'     => $ctg['meta'],
                    'grade_id' => $grade_id,
                ]);
            }
        }
    }
}
