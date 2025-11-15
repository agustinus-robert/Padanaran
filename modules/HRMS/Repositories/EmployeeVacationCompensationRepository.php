<?php

namespace Modules\HRMS\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\HRMS\Models\EmployeeVacationQuota;

trait EmployeeVacationCompensationRepository
{
    /**
     * Store newly created resource.
     */
    public function storeEmployeeVacationCompensation(Employee $employee, array $data)
    {
        $vacation = $employee->vacations()->create($data['vacation']);
        Auth::user()->log('menambahkan cuti karyawan ' . $employee->user->name . ' <strong>[ID: ' . $vacation->id . ']</strong>', EmployeeVacation::class, $vacation->id);

        // Assign approvable though employee positions
        foreach (config('modules.core.features.services.vacations.approvable_steps', []) as $model) {
            if ($model['type'] == 'parent_position_level') {
                if ($position = $employee->position->position->parents->firstWhere('level.value', $model['value'])?->employeePositions()->active()->first()) {
                    $vacation->createApprovable($position, ['result' => ApprovableResultEnum::APPROVE]);
                }
            }
        }

        foreach ($data['recaps'] as $key => $value) {
            $array[$key] = $key == 'result' ? array_merge($value, ['vacation' => $vacation->id]) : $value;
        }

        $recap = $employee->dataRecapitulations()->create($array);
        Auth::user()->log('menambahkan komponesasi cuti karyawan ' . $recap->employee->user->name . ' <strong>[ID: ' . $recap->id . ']</strong>', EmployeeDataRecapitulation::class, $recap->id);

        return $recap;
    }

    /**
     * Remove the current resource.
     */
    public function destroyEmployeeVacationCompensation(EmployeeVacation $vacation, EmployeeDataRecapitulation $recap)
    {
        if (!$vacation->trashed() && $vacation->delete()) {
            Auth::user()->log('menghapus cuti karyawan ' . $vacation->employee->user->name . ' <strong>[ID: ' . $vacation->id . ']</strong>', EmployeeVacation::class, $vacation->id);
            if (!$recap->trashed() && $recap->delete()) {
                Auth::user()->log('menghapus komponesasi cuti karyawan ' . $recap->employee->user->name . ' <strong>[ID: ' . $recap->id . ']</strong>', EmployeeDataRecapitulation::class, $recap->id);
                return $recap;
            }
            return $vacation;
        }
        return false;
    }

    public function extendQuota($quota_id, $date)
    {
        $quota = EmployeeVacationQuota::findorfail($quota_id);
        $quota->fill(['end_at' => $date]);

        if ($quota->save()) {
            Auth::user()->log('menambahkan perpanjangan kuota cuti karyawan ' . $quota->employee->user->name . ' <strong>[ID: ' . $quota->id . ']</strong>', EmployeeVacationQuota::class, $quota->id);
            return $quota;
        }
        return false;
    }
}
