<?php

namespace Modules\Poz\Database\Seeders;

use Modules\Account\Models\User;
use Illuminate\Database\Seeder;
use Modules\Poz\Models\Supplier;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Faker\Factory as Faker;

class SupplierDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

         $dataUsersSMP = [
            [
                'name' => 'Dina Martina',
                'position' => 12,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Sania Sari',
                'position' => 12,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Nisa Matutina',
                'position' => 12,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Doni Simanjuntak',
                'position' => 12,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Roni Mulyono',
                'position' => 12,
                'sex' => 1,
                'unit_id' => 4
            ],
        ];

        $dataUsersSMA = [
            [
                'name' => 'Robert Haryanto',
                'position' => 12,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Agusta Dwi Saptono',
                'position' => 12,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Adam Jaya',
                'position' => 12,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Anton Pujiharto',
                'position' => 12,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Dina Martha',
                'position' => 12,
                'sex' => 1,
                'unit_id' => 5
            ],
        ];

       

        $dataUsers = array_merge($dataUsersSMP, $dataUsersSMA);

        foreach ($dataUsers as $value) {
            $user = new User([
                'name' => $value['name'],
                'username' => $this->generateUsername($value['name']),
                'email' => $faker->unique()->safeEmail(),
                'password' => 'password',
                'current_team_id' => 1
            ]);
            $user->save();

            $empl = $user->employee()->create([
                'joined_at' => now(),
                'grade_id' => $value['unit_id']
            ]);

            $contract = $empl->contract()->create([
                'kd' => ($user->id + 1) . '/AFD-SUPPLIER/' . date('Y'),
                'contract_id' => 2,
                'work_location' => 1,
                'start_at' => '2021-01-01 01:00:00',
                'end_at' => null,
                'created_by' => User::first()->id,
                'updated_by' => User::first()->id
            ]);

            $contract->position()->create([
                'empl_id' => $contract->empl_id,
                'position_id' => 12,
                'start_at' => $contract->start_at,
                'end_at' => $contract->end_at,
            ]);

            $supplier = new Supplier([
                'code'       => strtoupper(substr($value['name'], 0, 3)) . rand(100, 999),
                'name'       => $value['name'] . ' Supplier',
                'email'      => $faker->unique()->companyEmail(),
                'phone'      => $faker->phoneNumber(),
                'address'    => $faker->address(),
                'location'   => $faker->city(),
                'image_name' => null,
                'user_id'    => $user->id,
                'created_by' => $user->id,
            ]);
            $supplier->save();

            $outletId = 1;
            if ($outletId) {
                $supplier->outlets()->attach($outletId);
            }
        }
    }


    private function generateUsername($name)
    {
        return strtolower(preg_replace('/\s+/', '', $name)) . rand(100, 999);
    }
}
