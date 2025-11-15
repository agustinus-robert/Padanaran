<?php

namespace Modules\HRMS\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\HRMS\Models\EmployeeScheduleSubmissionTeacher;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\HRMS\Models\EmployeeTeacherDuty;
use Modules\Core\Models\CompanyApprovable;
use Modules\Portal\Notifications\Schedule\Submission\AllocativeSubmissionNotification;

trait EmployeeCollectiveScheduleTeacherRepository
{
    /**
     * Store newly created resource.
     */
    public function storeEmployeeSchedule(array $data, $employee)
    {
        DB::beginTransaction();

        try {
            $userUdt = [];

            foreach ($data['data'] as $resultKeyData => $resultValueData) {

                foreach ($resultValueData as $keyDates => $resultDates) {

                    $startAt = Carbon::createFromFormat('Y-m-d', $keyDates . '-01', 'Asia/Jakarta')
                        ->startOfMonth()
                        ->format('Y-m-d');
                    $endAt = Carbon::createFromFormat('Y-m-d', $keyDates . '-01')
                        ->endOfMonth()
                        ->format('Y-m-d');

                    $result = [];

                    foreach ($resultDates as $date => $shifts) {
                        $in    = $shifts['in'] ?? null;
                        $out   = $shifts['out'] ?? null;
                        $shift = $shifts['shift'] ?? [];

                        $result[$date] = [$in, $out, $shift];
                    }

                    $existingRecord = EmployeeTeacherDuty::where([
                        'start_at' => $startAt,
                        'end_at' => $endAt,
                        'empl_id' => $resultKeyData
                    ])->first();


                    if ($existingRecord) {
                        $decodedDates = $existingRecord->dates ?? [];

                        foreach ($result as $date => $newShifts) {
                            $newIn    = $newShifts[0] ?? null;
                            $newOut   = $newShifts[1] ?? null;
                            $newShift = $newShifts[2] ?? [];

                            if (isset($decodedDates[$date]) && is_array($decodedDates[$date])) {
                                $existing = $decodedDates[$date];

                                $existing[0] = $newIn ?? $existing[0] ?? null;
                                $existing[1] = $newOut ?? $existing[1] ?? null;

                                $existingShifts = $existing[2] ?? [];
                                if (!is_array($existingShifts)) $existingShifts = [];
                                if (!is_array($newShift)) $newShift = [];

                                $existing[2] = collect($existingShifts + $newShift)->sortKeys()->all();
                            
                                $decodedDates[$date] = $existing;
                            } else {
                                $decodedDates[$date] = [$newIn, $newOut, $newShift];
                            }
                        }

                        $workCount = 0;
                        foreach ($decodedDates as $date => $values) {
                            if (!empty($values[0]) && !empty($values[1])) {
                                $workCount++;
                            }
                        }

                        $existingRecord->update([
                            'dates' => $decodedDates,
                            'workdays_count' => $workCount,
                        ]);
                    } else {
                        EmployeeTeacherDuty::create([
                            'empl_id' => $resultKeyData,
                            'start_at' => $startAt,
                            'end_at' => $endAt,
                            'dates' => $result,
                            'workdays_count' => 0,
                        ]);
                    }


                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            dd([
                'Error' => $e->getMessage(),
                'File' => $e->getFile(),
                'Line' => $e->getLine(),
                'Trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }
}
