<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\CompanyLoanCategory;

class LoanDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [4, 5];

        $loans = [
            [
                'name' => 'Bunga Pinjaman',
                'type' => 2,
            ],
            [
                'name'        => 'Cicilan Pinjaman',
                'interest_id' => 1,
                'type'        => 1,
            ],
            [
                'name' => 'Cash Bon',
                'type' => 1,
            ],
        ];

        foreach ($grades as $grade_id) {
            foreach ($loans as $loan) {
                $loan['grade_id'] = $grade_id;
                CompanyLoanCategory::create($loan);
            }
        }
    }
}
