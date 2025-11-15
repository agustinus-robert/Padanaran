<?php

namespace Modules\Account\Database\Seeders;

use Carbon\Carbon;
use Modules\Account\Models\User;
use Modules\Account\Models\UserProfile;
use Modules\Academic\Models\Student;
use DB;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $faker = Faker::create('id_ID');

        $usedUsernames = User::pluck('username')->toArray();
        $usedEmails    = User::pluck('email')->toArray();
        $usedNis       = Student::pluck('nis')->toArray();
        $usedNisn      = Student::pluck('nisn')->toArray();
        $usedNik       = Student::pluck('nik')->toArray();

        $grades = [4, 5];

        foreach ($grades as $gradeId) {
            for ($i = 0; $i < 300; $i++) {
                // Pastikan sex tidak kosong
                $sex = $faker->randomElement([1, 2]) ?? 1;

                // Nama sesuai jenis kelamin
                if ($sex === 1) {
                    $name = $faker->firstNameMale . ' ' . $faker->lastName;
                } else {
                    $name = $faker->firstNameFemale . ' ' . $faker->lastName;
                }

                // Username unik
                do {
                    $username = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                } while (in_array($username, $usedUsernames));
                $usedUsernames[] = $username;

                // Email unik
                do {
                    $email = $faker->safeEmail();
                } while (in_array($email, $usedEmails));
                $usedEmails[] = $email;

                // NIS unik
                do {
                    $nis = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
                } while (in_array($nis, $usedNis));
                $usedNis[] = $nis;

                // NISN unik
                do {
                    $nisn = str_pad(rand(1, 9999999), 7, '0', STR_PAD_LEFT);
                } while (in_array($nisn, $usedNisn));
                $usedNisn[] = $nisn;

                // NIK unik
                do {
                    $nik = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                } while (in_array($nik, $usedNik));
                $usedNik[] = $nik;

                // Simpan user
                $user = new User([
                    'name'             => $name,
                    'username'         => $username,
                    'email'            => $email,
                    'password'         => 'password',
                    'current_team_id'  => 1
                ]);

                if ($user->save()) {
                    $user->setMeta('profile_sex', $sex);

                    // Tambahkan student
                    $user->student()->create([
                        'nis'           => $nis,
                        'nisn'          => $nisn,
                        'nik'           => $nik,
                        'generation_id' => 38,
                        'entered_at'    => now()->format('Y-m-d'),
                        'grade_id'      => $gradeId, 
                    ]);

                    // Tambahkan user_profile
                    $user->profile()->create([
                        'name'       => $name,
                        'pob'        => $faker->city,
                        'dob'        => $faker->dateTimeBetween('2005-01-01', '2010-12-31')->format('Y-m-d'),
                        'sex'        => $sex,
                        'religion'   => 0,
                        'is_dead'    => false,
                        'nik'        => $nik,
                        'hobby_id'   => rand(1, 5),
                        'desire_id'  => rand(1, 3)
                    ]);
                }
            }
        }
    }
}
