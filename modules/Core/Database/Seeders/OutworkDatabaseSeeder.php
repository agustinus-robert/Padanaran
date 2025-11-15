<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\CompanyOutworkCategory;

class OutworkDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [4, 5];
        $outworks = [
            'Pelatihan/kursus' => [
                ['description' => 'Dalam negeri', 'price' => 17500, 'meta' => ['in_working_hours_price' => 14000]],
                ['description' => 'Luar negeri', 'price' => 26500, 'meta' => ['in_working_hours_price' => 21000]]
            ],
            'Sertifikasi' => [
                ['description' => 'Dalam negeri', 'price' => 17500, 'meta' => ['in_working_hours_price' => 14000]],
                ['description' => 'Luar negeri', 'price' => 26500, 'meta' => ['in_working_hours_price' => 21000]]
            ],
            'Webinar/seminar' => [
                ['description' => 'Dalam negeri', 'price' => 17500, 'meta' => ['in_working_hours_price' => 14000]],
                ['description' => 'Luar negeri', 'price' => 26500, 'meta' => ['in_working_hours_price' => 21000]]
            ],
            'Webinar/seminar' => [
                ['description' => 'dalam negeri', 'price' => 17500, 'meta' => ['in_working_hours_price' => 14000]],
                ['description' => 'Luar negeri', 'price' => 26500, 'meta' => ['in_working_hours_price' => 21000]]
            ],
            'Perjalanan dinas' => [
                ['description' => 'Dalam negeri', 'price' => 43750, 'meta' => ['in_working_hours_price' => 35000]],
                ['description' => 'Luar negeri', 'price' => 65625, 'meta' => ['in_working_hours_price' => 52500]]
            ],
            'Asistensi' => [
                ['description' => 'Dalam negeri', 'price' => 43750, 'meta' => ['in_working_hours_price' => 35000]],
                ['description' => 'Luar negeri', 'price' => 65625, 'meta' => ['in_working_hours_price' => 52500]]
            ],
            'Kegiatan non bisnis' => [
                ['description' => 'Internal', 'price' => 75000, 'meta' => ['prepareable' => true]],
                ['description' => 'Eksternal', 'price' => 100000, 'meta' => ['prepareable' => true]]
            ]
        ];

        foreach ($grades as $grade_id) {
            foreach ($outworks as $name => $_o) {
                foreach ($_o as $outwork) {
                    CompanyOutworkCategory::create(
                        array_merge(
                            compact('name', 'grade_id'),
                            $outwork
                        )
                    );
                }
            }
        }
    }
}
