<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\CompanyLeaveCategory;
use Illuminate\Database\Seeder;

class LeaveDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leave_categories = [
            [
                'name' => 'Dinas/Tugas Perusahaan'
            ],
            [
                'name' => 'Meninggalkan Jam Kerja (kembali ke kantor)',
                'meta' => ['quota' => 1, 'time_input' => 'start_to_end']
            ],
            [
                'name' => 'Pulang cepat',
                'meta' => ['quota' => 1, 'time_input' => 'start_only']
            ],
            [
                'name' => 'Sakit',
            ],
            [
                'name' => 'Masa Haid',
                'meta' => ['quota' => 2]
            ],
            [
                'name' => 'Keperluan Keluarga',
                'children' => [
                    [
                        'name' => 'Karyawan Menikah',
                        'meta' => ['quota' => 5]
                    ],
                    [
                        'name' => 'Menikahkan Anak',
                        'meta' => ['quota' => 3]
                    ],
                    [
                        'name' => 'Istri Melahirkan',
                        'meta' => ['quota' => 3]
                    ],
                    [
                        'name' => 'Khitanan Anak',
                        'meta' => ['quota' => 2]
                    ],
                    [
                        'name' => 'Akikah Anak',
                        'meta' => ['quota' => 1]
                    ],
                    [
                        'name' => 'Baptis Anak/Suami/Istri',
                        'meta' => ['quota' => 1]
                    ],
                    [
                        'name' => 'Istri Gugur Kandungan',
                        'meta' => ['quota' => 3]
                    ],
                    [
                        'name' => 'Kematian Suami/Istri/Anak/Saudara Kandung',
                        'meta' => ['quota' => 3]
                    ],
                    [
                        'name' => 'Kematian Orang Tua/Mertua',
                        'meta' => ['quota' => 3]
                    ],
                    [
                        'name' => 'Kematian Anggota Keluarga Serumah',
                        'meta' => ['quota' => 1]
                    ]
                ]
            ]
        ];

        $grades = [4, 5];

        foreach ($grades as $grade) {
            foreach ($leave_categories as $ctg) {
                $category = CompanyLeaveCategory::create([
                    'name'  => $ctg['name'],
                    'meta'  => $ctg['meta'] ?? null,
                    'grade_id' => $grade,
                ]);

                if (isset($ctg['children'])) {
                    foreach ($ctg['children'] as $_ctg) {
                        $_ctg['parent_id'] = $category->id;
                        $_ctg['grade_id']     = $grade;
                        CompanyLeaveCategory::create($_ctg);
                    }
                }
            }
        }
    }
}
