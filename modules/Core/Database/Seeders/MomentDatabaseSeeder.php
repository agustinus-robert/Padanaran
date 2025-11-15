<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Core\Models\CompanyMoment;

class MomentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        $endpoint = 'https://api-harilibur.vercel.app/api';
        if ($responses = Http::withOptions(['verify' => false])->get($endpoint)->collect()) {
            try {
                foreach ($responses as $respons) {
                    CompanyMoment::updateOrCreate([
                        'name' => $respons['holiday_name'],
                        'date' => $respons['holiday_date'],
                    ], [
                        'type' => $respons['is_national_holiday'] ? 1 : 5,
                        'is_holiday' => $respons['is_national_holiday'],
                        'meta' => NULL
                    ]);
                }
                DB::commit();
                return true;
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error('Proses gagal dilanjutkan!', ['error' => $th->getMessage()]);
                return false;
            }
        }
    }
}
