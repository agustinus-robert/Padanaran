<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\User;
use Modules\HRMS\Models\Employee;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (env('DB_SEED')) {
            $this->call([
                AppDatabaseSeeder::class,
                TaxRatesSeeder::class,
                \Modules\Academic\Database\Seeders\AcademicDatabaseSeeder::class,
                \Modules\Administration\Database\Seeders\AdministrationDatabaseSeeder::class,

                \Modules\Core\Database\Seeders\MomentDatabaseSeeder::class,
              //  \Modules\Reference\Database\Seeders\ReferenceDatabaseSeeder::class,
                \Modules\Core\Database\Seeders\CoreDatabaseSeeder::class,
                \Modules\Account\Database\Seeders\AccountDatabaseSeeder::class,
                \Modules\Core\Database\Seeders\DepartementDatabaseSeeder::class,
                \Modules\Core\Database\Seeders\ContractDatabaseSeeder::class,
                \Modules\HRMS\Database\Seeders\HRMSDatabaseSeeder::class,
                \Modules\Core\Database\Seeders\VacationDatabaseSeeder::class,
                \Modules\Core\Database\Seeders\LeaveDatabaseSeeder::class,
                \Modules\Core\Database\Seeders\InsuranceDatabaseSeeder::class,
                \Modules\Core\Database\Seeders\OutworkDatabaseSeeder::class,
                \Modules\Core\Database\Seeders\SlipDatabaseSeeder::class,
                \Modules\Core\Database\Seeders\LoanDatabaseSeeder::class,
                \Modules\Account\Database\Seeders\UserDatabaseSeeder::class,
                \Modules\Account\Database\Seeders\StudentDatabaseSeeder::class,
                \Modules\Poz\Database\Seeders\ProductDatabaseSeeder::class,
                \Modules\Poz\Database\Seeders\SupplierDatabaseSeeder::class,
                \Modules\Administration\Database\Seeders\AdministrationBillDatabaseSeeder::class
                // \Modules\Account\Database\Seeders\UserDatabaseSeeder::class,
             //   \Modules\Pos\Database\Seeders\ProductDatabaseSeeder::class,
            ]);


            //$users = User::all();

            // foreach ($users as $user) {
            //     $user->employee()->create([
            //         'joined_at' => Carbon::parse(now()),
            //     ]);
            // }

            // $employees = Employee::all();

            // foreach ($employees as $key => $empl) {
            //     if ($key === 0) {
            //         $contract = $empl->contract()->create([
            //             'kd' => ($key + 1) . '/DIGIPEMAD/TEST/'.date('Y'),
            //             'contract_id' => 2,
            //             'work_location' => 1,
            //             'start_at' => '2021-01-01 01:00:00',
            //             'end_at' => null,
            //             'created_by' => $users->first()->id,
            //             'updated_by' => $users->first()->id
            //         ]);
            //     } else {
            //         $contract = $empl->contract()->create([
            //             'kd' => ($key + 1) . '/DIGIPEMAD/TEST/'.date('Y'),
            //             'contract_id' => 1,
            //             'work_location' => 1,
            //             'start_at' => '2022-01-01 01:00:00',
            //             'end_at' => '2024-12-31 23:59:00',
            //             'created_by' => $users->first()->id,
            //             'updated_by' => $users->first()->id
            //         ]);
            //     }
            // }

            // foreach ($employees as $key => $empl) {
            //     $contract = $empl->contract;
            //     if ($key === 0) {
            //         $empl->position()->create([
            //             'position_id' => 1,
            //             'contract_id' => $contract->id,
            //             'start_at' => $contract->start_at,
            //             'end_at' => $contract->end_at,
            //         ]);
            //     } else if ($key > 0 && $key <= 22) {
            //         $empl->position()->create([
            //             'position_id' => $key + 1,
            //             'contract_id' => $contract->id,
            //             'start_at' => $contract->start_at,
            //             'end_at' => $contract->end_at,
            //         ]);
            //     } else {
            //         $empl->position()->create([
            //             'position_id' => 21,
            //             'contract_id' => $contract->id,
            //             'start_at' => $contract->start_at,
            //             'end_at' => $contract->end_at,
            //         ]);
            //     }
            // }
        }
    }
}
