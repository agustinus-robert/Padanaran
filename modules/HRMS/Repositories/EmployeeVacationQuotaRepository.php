<?php

namespace Modules\HRMS\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Account\Enums\SexEnum;
use Modules\Core\Models\CompanyVacationCategory;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeContract;
use Modules\HRMS\Models\EmployeeVacationQuota;

trait EmployeeVacationQuotaRepository
{
    /**
     * Store newly created resource.
     */
    public function storeEmployeeVacationQuota(array $data)
    {
        $employee = Employee::find($data['empl_id']);

        if ($employee->vacationQuotas()->saveMany(array_map(
            fn($quota) => new EmployeeVacationQuota(array_merge($quota, [
                'grade_id' => $employee->grade_id
            ])),
            $data['quotas']
        ))) {
            Auth::user()->log('membuat distribusi kuota cuti karyawan ' . $employee->user->name . ' <strong>[ID: ' . $employee->id . ']</strong>', Employee::class, $employee->id);
            return $employee;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyEmployeeVacationQuota(EmployeeVacationQuota $quota)
    {
        if ($quota->delete()) {
            Auth::user()->log('menghapus distribusi kuota cuti karyawan ' . $quota->employee->user->name . ' <strong>[ID: ' . $quota->id . ']</strong>', EmployeeContract::class, $quota->id);
            return $quota;
        }
        return false;
    }

    /**
     * generate vacation this year.
     */
    public function generateVacationThisYear($year)
    {
        $currentDate = date($year . '-m-d');
        $start_at = Carbon::parse($currentDate)->startOfYear();
        $end_at = Carbon::parse($currentDate)->endOfYear();

        DB::beginTransaction();

        try {
            // Mengambil semua karyawan dengan relasi 'position' dan 'user.meta'
            $employees = Employee::with('position.position', 'user.meta')
                ->where('grade_id', userGrades())
                ->whereHas('contract')
                ->get();

            foreach ($employees as $employee) {
                // Mengambil kuota cuti karyawan pada tahun yang ditentukan
                $quota = $employee->vacationQuotas()
                    ->whereDate('start_at', $start_at->format('Y-m-d'))
                    ->whereDate('end_at', $end_at->format('Y-m-d'))
                    ->first();

                // Jika kuota cuti belum ada, tambahkan sesuai jenis kelamin karyawan
                if (empty($quota)) {
                    $dataQuota = $this->addQuotaByGender($employee->user->getMeta('profile_sex'), $start_at, $end_at);
                    $employee->vacationQuotas()->createMany($dataQuota);
                    Log::info('menambahkan kuota cuti karyawan ' . $employee->user->name, ['data' => $dataQuota]);
                }
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('danger', 'Terjadi kegagalan, error code:' . $th->getMessage());
        }
    }

    public function addQuotaByGender($gender, $start_at, $end_at): array
    {
        switch ($gender) {
            case SexEnum::FEMALE->value:
                $ctgs = CompanyVacationCategory::all();
                break;

            default:
                $ctgs = CompanyVacationCategory::whereIn('id', [1, 4])->get();
                break;
        }

        foreach ($ctgs as $key => $value) {
            $quota[$key] = [
                'start_at' => $start_at,
                'end_at' => $end_at,
                'ctg_id' => $value->id,
                'quota' => $value->meta?->quota,
                'visible_at' => $start_at,
            ];
        }

        return $quota;
    }
}
