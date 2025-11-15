<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\User;
use Modules\HRMS\Models\Employee;

class TenantSeeder extends Seeder
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
                \Modules\Account\Database\Seeders\AccountDatabaseSeeder::class
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
