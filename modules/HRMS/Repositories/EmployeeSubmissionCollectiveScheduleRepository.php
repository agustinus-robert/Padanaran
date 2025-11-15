<?php

namespace Modules\HRMS\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Models\EmployeeScheduleSubmission;
use Modules\HRMS\Models\EmployeeSchedule;
use Illuminate\Support\Collection;
use Modules\HRMS\Models\EmployeeScanLog;
use Modules\HRMS\Enums\TeacherShiftEnum;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Enums\ObShiftEnum;


trait EmployeeSubmissionCollectiveScheduleRepository
{
    /**
     * Store newly created resource.
     */
    public function storeEmployeeSchedule(array $data)
    {
        $success = true;  
        
        
        $startAt = date('Y-m-d', strtotime($data['start_at']));
        $endAt = date('Y-m-d', strtotime($data['end_at']));

        try {
            $scheduleTmp = EmployeeScheduleSubmission::findOrFail($data['id']);
            $scheduleTmp->approved_at = now(); 
            $scheduleTmp->save();


            $existingRecord = EmployeeSchedule::where(['start_at' => $startAt, 'end_at' => $endAt, 'empl_id' => $data['empl_id']])
                ->first();

            if ($existingRecord) {
                $existingRecord->update([
                    'dates' => $data['dates'],
                    'workdays_count' => $data['workdays_count']
                ]);
            } else {
                EmployeeSchedule::create([
                    'empl_id' => $data['empl_id'],
                    'start_at' => $startAt,
                    'end_at' => $endAt,
                    'dates' => $data['dates'],
                    'workdays_count' => $data['workdays_count']
                ]);
            }
        } catch (\Exception $e) {
            $success = false;
            dd($e->getMessage());
            return \Log::error('Error saat menyimpan data: ' . $e->getMessage());
        }

        return $success;
    }
}