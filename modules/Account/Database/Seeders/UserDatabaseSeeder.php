<?php

namespace Modules\Account\Database\Seeders;

use Carbon\Carbon;
use Modules\Account\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataUsersSMP = [
            // pengasuh pondok
            [
                'name' => 'Slamet Riyadi',
                'position' => 1,
                'sex' => 1,
                'unit_id' => 4
            ],
            // pengurus pondok
            [
                'name' => 'Bayu Saputra',
                'position' => 12,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Agus Pratama',
                'position' => 12,
                'sex' => 2,
                'unit_id' => 4
            ],
            // kepala sekolah
            [
                'name' => 'Sutrisno Hadi',
                'position' => 3,
                'sex' => 1,
                'unit_id' => 4
            ],
            // humas
            [
                'name' => 'Bagus Wijaya',
                'position' => 4,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Ratna Sari',
                'position' => 4,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Arif Susanto',
                'position' => 4,
                'sex' => 1,
                'unit_id' => 4
            ],
            // Tata usaha / administrasi
            [
                'name' => 'Sri Wahyuni',
                'position' => 5,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Dewi Kartika',
                'position' => 5,
                'sex' => 1,
                'unit_id' => 4
            ],
            // finance
            [
                'name' => 'Rizka Amelia',
                'position' => 6,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Andi Kurniawan',
                'position' => 6,
                'sex' => 1,
                'unit_id' => 4
            ],
            // guru
            [
                'name' => 'Lestari Anggraini',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Ayu Pratiwi',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Rizky Setiawan',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Hendro Wibowo',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Fajar Saputra',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Wulandari Putri',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Kartika Dewi',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Aulia Rahma',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Novi Handayani',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Yudi Santoso',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Dedi Irawan',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Rahmat Hidayat',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Nur Aisyah',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Fitriani Putri',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Galih Pratama',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Melati Sari',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Rosita Amelia',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Ilham Saputra',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Nurul Hidayah',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Siti Aminah',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 4
            ],
            // guru BK
            [
                'name' => 'Dewi Anggraeni',
                'position' => 8,
                'sex' => 2,
                'unit_id' => 4
            ],
            // kasir toko
            [
                'name' => 'Siti Marlina',
                'position' => 10,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Heri Santoso',
                'position' => 10,
                'sex' => 1,
                'unit_id' => 4
            ],
            // kasir swalayan
            [
                'name' => 'Arman Prasetyo',
                'position' => 11,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Dina Lestari',
                'position' => 11,
                'sex' => 2,
                'unit_id' => 4
            ],
            // ustad
            [
                'name' => 'Abdul Karim',
                'position' => 14,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Hasan Basri',
                'position' => 14,
                'sex' => 1,
                'unit_id' => 4
            ],
            // ustadzah
            [
                'name' => 'Salsabila Putri',
                'position' => 15,
                'sex' => 2,
                'unit_id' => 4
            ],
            [
                'name' => 'Aisyah Zahra',
                'position' => 16,
                'sex' => 2,
                'unit_id' => 4
            ],
            // keamanan
            [
                'name' => 'Sugeng Riyadi',
                'position' => 17,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Kusnadi Saputra',
                'position' => 17,
                'sex' => 1,
                'unit_id' => 4
            ],
            [
                'name' => 'Rukmini Sari',
                'position' => 18,
                'sex' => 2,
                'unit_id' => 4
            ]
        ];


        $dataUsersSMA = [
            //pengasuh pondok
            [
                'name' => 'Joko Santoso',
                'position' => 1,
                'sex' => 1,
                'unit_id' => 5
            ],
            //pengurus pondok
            [
                'name' => 'Cahyo Pratama Nugroho',
                'position' => 12,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Ammar Hidayat Ramadhan',
                'position' => 12,
                'sex' => 2,
                'unit_id' => 5
            ],
            // kepala sekolah
            [
                'name' => 'Darmo Santoso',
                'position' => 3,
                'sex' => 1,
                'unit_id' => 5
            ],
            //humas
            [
                'name' => 'Dayat Nugroho',
                'position' => 4,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Sarinah Putri',
                'position' => 4,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Rohmat Fadli',
                'position' => 4,
                'sex' => 1,
                'unit_id' => 5
            ],
            //Tata usaha/administrasi
            [
                'name' => 'Maya Sari',
                'position' => 5,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Diana Ayu',
                'position' => 5,
                'sex' => 1,
                'unit_id' => 5
            ],
            //finance
            [
                'name' => 'Erika Amelia Putri',
                'position' => 6,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Zulfikar Ramadhan',
                'position' => 6,
                'sex' => 1,
                'unit_id' => 5
            ],
            //guru
            [
                'name' => 'Linda Kusuma Dewi',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Mina Rahma Putri',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Jodi Prasetyo Santoso',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Budi Santoso',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Roni Saputra',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Surti Wulandari',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Parni Lestari',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Anita Dewi',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Sopi Handayani',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Rudi Kurniawan',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Vino Aditya',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Samsudin Hadi',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Marni Puspita',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Wina Safitri',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Dani Putra',
                'position' => 7,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Gina Marlina',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Rita Sulastri',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Ridho Firmansyah',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Surti Lestari',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Ijah Lestari',
                'position' => 7,
                'sex' => 2,
                'unit_id' => 5
            ],
            //guru BK
            [
                'name' => 'Rina Andriyani',
                'position' => 8,
                'sex' => 2,
                'unit_id' => 5
            ],
            //kasir toko
            [
                'name' => 'Eni Marlina',
                'position' => 10,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Samsul Hadi',
                'position' => 10,
                'sex' => 1,
                'unit_id' => 5
            ],
            //kasir swalayan
            [
                'name' => 'Doni Prasetyo',
                'position' => 11,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Mikha Ayu',
                'position' => 11,
                'sex' => 2,
                'unit_id' => 5
            ],
            //ustad
            [
                'name' => 'Muhammad Jafar Santoso',
                'position' => 14,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Jawad Assegaf Rahman',
                'position' => 14,
                'sex' => 1,
                'unit_id' => 5
            ],
            //ustadzah
            [
                'name' => 'Yasmin Al Juffree',
                'position' => 15,
                'sex' => 2,
                'unit_id' => 5
            ],
            [
                'name' => 'Inaya Fatimah Putri',
                'position' => 16,
                'sex' => 2,
                'unit_id' => 5
            ],
            //keamanan
            [
                'name' => 'Yanto Wijaya',
                'position' => 17,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Sutomo Hadi',
                'position' => 17,
                'sex' => 1,
                'unit_id' => 5
            ],
            [
                'name' => 'Harni Susanti',
                'position' => 18,
                'sex' => 2,
                'unit_id' => 5
            ]
        ];



        $dataUsers = array_merge($dataUsersSMP, $dataUsersSMA);


        $faker = Faker::create();

        foreach ($dataUsers as $key => $value) {
            $user = new User([
                'name' => $value['name'],
                'username' => $this->generateUsername($value['name']),
                'email' => $faker->unique()->safeEmail(),
                'password' => 'password',
                'current_team_id' => 1
            ]);

            if ($user->save()) {
                $user->setMeta('profile_sex', $value['sex']);
                $empl = $user->employee()->create([
                    'joined_at' => Carbon::parse(now()),
                    'grade_id' => $value['unit_id']
                ]);

                $contract = $empl->contract()->create([
                    'kd' => ($key + 1) . '/AVD/'.$value['unit_id'].'/'.date('Y'),
                    'contract_id' => 2,
                    'work_location' => 1,
                    'start_at' => '2021-01-01 01:00:00',
                    'end_at' => null,
                    'created_by' => User::first()->id,
                    'updated_by' => User::first()->id
                ]);

                $contract->position()->create([
                    'empl_id' => $contract->empl_id,
                    'position_id' => $value['position'],
                    'start_at' => $contract->start_at,
                    'end_at' => $contract->end_at,
                ]);
            }
        }
    }

    public function generateUsername($name)
    {
        $nameWithoutSpaces = str_replace(' ', '', $name);
        $username = strtolower(substr($nameWithoutSpaces, 0, 8));
        return $username;
    }
};
