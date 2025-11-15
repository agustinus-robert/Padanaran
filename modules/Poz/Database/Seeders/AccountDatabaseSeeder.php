<?php

namespace Modules\Account\Database\Seeders;

use Modules\Account\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(5)->create();

        $user = User::first();
        $user->update(['username' => 'admin']);
        $user->setMeta('profile_religion', rand(1, 2));
        $user->employee()->create([
            'joined_at' => now(),
            'permanent_at' => now()
        ]);

        DB::insert('insert into user_roles (role_id, user_id) values (?, ?)', [1, $user->id]);
    }
}
