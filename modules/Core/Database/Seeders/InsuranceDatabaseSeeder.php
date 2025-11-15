<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\CompanyInsurance;
use Modules\Core\Models\CompanyInsurancePrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InsuranceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insurances = [
            'BPJS Kesehatan' => [
                [
                    'key' => 'group',
                    'label' => 'Kelas BPJS',
                    'type' => 'select',
                    'values' => ['Kelas 1', 'Kelas 2', 'Kelas 3']
                ],
                [
                    'key' => 'membership',
                    'label' => 'Kepesertaan',
                    'type' => 'select',
                    'values' => ['Mandiri', 'Penerima Upah', 'PBI']
                ],
                [
                    'key' => 'uncovered',
                    'label' => 'Anggota Keluarga Tidak Tertanggung',
                    'type' => 'select',
                    'values' => ['1 orang', '2 orang', '3 orang', '4 orang']
                ]
            ],
            'BPJS Ketenagakerjaan' => [
                [
                    'key' => 'services',
                    'label' => 'Jaminan yang diikuti',
                    'type' => 'select',
                    'values' => ['Jaminan Hari Tua', 'Jaminan Kecelakaan', 'Jaminan Kematian', 'Jaminan Pensiun',]
                ]
            ]
        ];

        $grades = [4, 5];

        foreach ($grades as $grade) {
            foreach ($insurances as $name => $meta) {
                CompanyInsurance::create([
                    'kd'    => Str::slug($name) . '-' . $grade,
                    'name'  => $name,
                    'grade_id' => $grade,
                    'meta'  => [
                        'conditions' => $meta
                    ],
                ]);
            }
        }


        $insurance_prices = [
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 1'], 'membership' => ['Mandiri']],
                'empl_price' => 150000,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 1'], 'membership' => ['Penerima Upah']],
                'cmp_price' => 4,
                'cmp_price_type' => 2,
                'empl_price' => 1,
                'empl_price_type' => 2,
                'price_factor' => 4037500,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 1'], 'uncovered' => ['1 orang']],
                'empl_price' => 1,
                'empl_price_type' => 2,
                'price_factor' => 4037500,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 1'], 'uncovered' => ['2 orang']],
                'empl_price' => 2,
                'empl_price_type' => 2,
                'price_factor' => 4037500,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 1'], 'uncovered' => ['3 orang']],
                'empl_price' => 3,
                'empl_price_type' => 2,
                'price_factor' => 4037500,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 1'], 'uncovered' => ['4 orang']],
                'empl_price' => 4,
                'empl_price_type' => 2,
                'price_factor' => 4037500,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 2'], 'membership' => ['Mandiri']],
                'empl_price' => 100000,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 2'], 'membership' => ['Penerima Upah']],
                'cmp_price' => 4,
                'cmp_price_type' => 2,
                'empl_price' => 1,
                'empl_price_type' => 2,
                'price_factor' => 2001000,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 2'], 'uncovered' => ['1 orang']],
                'empl_price' => 1,
                'empl_price_type' => 2,
                'price_factor' => 2001000,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 2'], 'uncovered' => ['2 orang']],
                'empl_price' => 2,
                'empl_price_type' => 2,
                'price_factor' => 2001000,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 2'], 'uncovered' => ['3 orang']],
                'empl_price' => 3,
                'empl_price_type' => 2,
                'price_factor' => 2001000,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 2'], 'uncovered' => ['4 orang']],
                'empl_price' => 4,
                'empl_price_type' => 2,
                'price_factor' => 2001000,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 3'], 'membership' => ['Mandiri']],
                'empl_price' => 35000,
            ],
            [
                'insurance_id' => 1,
                'conditions' => ['group' => ['Kelas 3'], 'membership' => ['PBI'], 'uncovered' => ['0 orang']],
            ],
            [
                'insurance_id' => 2,
                'conditions' => ['services' => ['Jaminan Hari Tua']],
                'cmp_price' => 3.7,
                'cmp_price_type' => 2,
                'empl_price' => 2,
                'empl_price_type' => 2,
                'price_factor_additional' => ['gaji-pokok']
            ],
            [
                'insurance_id' => 2,
                'conditions' => ['services' => ['Jaminan Kecelakaan']],
                'cmp_price' => 0.24,
                'cmp_price_type' => 2,
                'price_factor_additional' => ['gaji-pokok']
            ],
            [
                'insurance_id' => 2,
                'conditions' => ['services' => ['Jaminan Kematian']],
                'cmp_price' => 0.3,
                'cmp_price_type' => 2,
                'price_factor_additional' => ['gaji-pokok']
            ],
            [
                'insurance_id' => 2,
                'conditions' => ['services' => ['Jaminan Pensiun']],
                'cmp_price' => 2,
                'cmp_price_type' => 2,
                'empl_price' => 1,
                'empl_price_type' => 2,
                'price_factor_additional' => ['gaji-pokok']
            ]
        ];

        foreach ($insurance_prices as $price) {
            CompanyInsurancePrice::create($price);
        }
    }
}
