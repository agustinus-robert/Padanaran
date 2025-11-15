<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\CompanyPtkp;
use Modules\Account\Enums\MariageEnum;

class PtkpDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [4, 5];
        $taxPtkpClassification = [
            'TER Kategori A' => [
                [
                    'sex' => 3,
                    'mariage' => MariageEnum::SINGLE,
                    'child' => 0,
                    'value' =>  54000000
                ],
                [
                    'sex' => 3,
                    'mariage' => MariageEnum::SINGLE,
                    'child' => 1,
                    'value' => 58500000
                ],
                [
                    'sex' => 3,
                    'mariage' => MariageEnum::MARRY,
                    'child' => 1,
                    'value' => 58500000
                ],
            ],
            'TER Kategori B' => [
                [
                    'sex' => 3,
                    'mariage' => MariageEnum::SINGLE,
                    'child' => 2,
                    'value' => 63000000
                ],
                [
                    'sex' => 3,
                    'mariage' => MariageEnum::SINGLE,
                    'child' => 3,
                    'value' => 67500000
                ],
                [
                    'sex' => 3,
                    'mariage' => MariageEnum::MARRY,
                    'child' => 2,
                    'value' => 63000000
                ],
                [
                    'sex' => 3,
                    'mariage' => MariageEnum::MARRY,
                    'child' => 2,
                    'value' => 67500000
                ],
            ],
            'TER Kategori C' => [
                [
                    'sex' => 3,
                    'mariage' => MariageEnum::MARRY,
                    'child' => 3,
                    'value' => 67500000
                ]
            ]
        ];

        foreach ($grades as $grade_id) {
            foreach ($taxPtkpClassification as $name => $_o) {
                foreach ($_o as $ptkps) {
                    CompanyPtkp::create([
                        'sex'      => $ptkps['sex'],
                        'mariage'  => $ptkps['mariage'],
                        'child'    => $ptkps['child'],
                        'value'    => $ptkps['value'],
                        'grade_id' => $grade_id,
                    ]);
                }
            }
        }
    }
}
