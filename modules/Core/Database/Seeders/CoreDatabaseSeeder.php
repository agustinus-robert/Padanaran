<?php

namespace Modules\Core\Database\Seeders;

use App\Models\Permission;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Modules\Core\Models\CompanyRole;

class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->runSettingSeeder();
    }

    /**
     * Run the database settings seeds.
     *
     * @return void
     */
    public function runSettingSeeder()
    {
        $settings = [
            'cmp_short_name' => 'EI',
            'cmp_name' => 'Avada',
            'cmp_long_name' => 'Avada',
            'cmp_cutoff_date' => [
                ['first day of last month midnightZ', '+19 days'],
                ['first day of this month midnightZ', '+19 days', '-1 second']
            ],
        ];

        foreach ($settings as $key => $value) {
            Setting::create(compact('key', 'value'));
        }

        $grades = [4, 5];

        foreach ($grades as $grade) {
            $role = new CompanyRole([
                'kd' => 'root',
                'name' => 'Super Administrator',
                'grade_id' => $grade
            ]);
        }

        $role->save();

        $role->permissions()->attach(Permission::all()->pluck('id'));
    }
}
