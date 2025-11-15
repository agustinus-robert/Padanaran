<?php

namespace Modules\HRMS\Repositories;

use Arr;
use Auth;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeContract;
use Modules\HRMS\Models\EmployeeContractSchedule;

trait EmployeeContractScheduleRepository
{
    /**
     * Store newly created resource.
     */
    public function storeEmployeeContractSchedule(array $data)
    {        
        $schedule = new EmployeeContractSchedule(Arr::only($data, ['contract_id', 'start_at', 'end_at', 'dates', 'workdays_count']));

        if($schedule->save()) {
            Auth::user()->log('membuat jadwal kerja karyawan '.$schedule->contract->employee->user->name.' <strong>[ID: '.$schedule->id.']</strong>', EmployeeContractSchedule::class, $schedule->id);
            return $schedule;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateEmployeeContractSchedule(EmployeeContractSchedule $schedule, array $data)
    {
        $schedule->fill(Arr::only($data, ['dates', 'workdays_count']));

        if($schedule->save()) {
            Auth::user()->log('memperbarui jadwal kerja karyawan '.$schedule->contract->employee->user->name.' <strong>[ID: '.$schedule->id.']</strong>', EmployeeContractSchedule::class, $schedule->id);
            return $schedule;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyEmployeeContractSchedule(EmployeeContractSchedule $schedule)
    {
        if($schedule->delete()) {
            Auth::user()->log('menghapus jadwal kerja karyawan '.$schedule->contract->employee->user->name.' <strong>[ID: '.$schedule->id.']</strong>', EmployeeContractSchedule::class, $schedule->id);
            return $schedule;
        }
        return false;
    }
}
