<?php

namespace Modules\Reference\Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ReferenceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__.'/../Migrations/references.sql'));
    }
}
