<?php

namespace Modules\HRMS\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\HRMS\Models\EmployeeScheduleSubmissionTeacher;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\Core\Models\CompanyApprovable;
use Modules\Portal\Notifications\Schedule\Submission\AllocativeSubmissionNotification;

trait EmployeeCollectiveTeacherImportExcel
{
    /**
     * Store newly created resource.
     */
    private function totalMonthly($dataShiftUser)
    {

        $userShift = [];
        $totalMonthly = [];
        foreach ($dataShiftUser as $user => $monthLy) {
            $userShift[$user] = [];
            $totalMonthly[$user] = [];
            foreach ($monthLy as $dateKey => $dateValue) {
                $userShift[$user][$dateKey] = [];
                $totalMonthly[$user][$dateKey] = 0;
                foreach ($dateValue as $shiftKey => $shiftValue) {
                    $userShift[$user][$dateKey][$shiftKey] = 0;
                    $validShifts = 0;
                    foreach ($shiftValue as $shift) {
                        if ($shift !== [null, null]) {
                            $validShifts++;
                            $totalMonthly[$user][$dateKey]++;
                        }
                    }

                    if ($userShift[$user][$dateKey][$shiftKey]) {
                        $userShift[$user][$dateKey][$shiftKey] = 0;
                    }


                    $userShift[$user][$dateKey][$shiftKey] += $validShifts;
                }
            }
        }

        return $totalMonthly;
    }
    public function storeEmployeeSchedule(array $data, $employee)
    {
        $dataSh = [];
        DB::beginTransaction();

        try {

            $shiftDayCount = [];
            $result = [];
            $userUdt = [];

            foreach ($data['data'] as $resultKeyData => $resultValueData) {
                foreach ($resultValueData as $keyDates => $resultDates) {
                    $result = [];
                    $startAt = Carbon::createFromFormat('Y-m-d', $keyDates . '-01', 'Asia/Jakarta')
                        ->startOfMonth()
                        ->format('Y-m-d');
                    $endAt = Carbon::createFromFormat('Y-m-d', $keyDates . '-01')
                        ->endOfMonth()
                        ->format('Y-m-d');


                    foreach ($resultDates as $date => $shifts) {
                        $formattedShifts = [];
                        foreach ($shifts as $shift) {
                            $formattedShifts[] = $shift;
                        }
                        $result[$date] = $formattedShifts;
                    }


                    $jsonResult = json_encode($result);



                    $existingRecord = EmployeeScheduleTeacher::where(['start_at' => $startAt, 'end_at' => $endAt, 'empl_id' => $resultKeyData])->first();

                    if ($existingRecord) {
                        $dateShifts = [];
                        $existingShifts = json_decode($existingRecord->dates, true);

                        foreach ($data['updateShifts'] as $dateUdt => $shiftsUdt) {
                            $dateShifts[$dateUdt] = array_combine(
                                range(0, count($shiftsUdt) - 1),
                                array_values($shiftsUdt)
                            );
                        }

                        $decodedDates = json_decode($existingRecord->dates, true);
                        $arr = [];
                        foreach ($result as $date => $shifts) {

                            if (isset($dateShifts[$date])) {

                                $decodedDates[$date] = $shifts;
                                $userUdt[$resultKeyData][$keyDates] = $decodedDates;
                            }
                        }


                        $arrUdt['dates'] = $decodedDates;
                        $arrUdt['workdays_count'] = $this->totalMonthly($userUdt)[$resultKeyData][$keyDates];
                        $existingRecord->update($arrUdt);

                        //$existingRecord->employee->user->notify(new AllocativeSubmissionNotification('Penjadwalan Anda telah ditambahkan oleh ' . $existingRecord->creator->user->name . ', silakan cek pada link berikut ' . route('portal::schedule.submission.show', ['submission' => $existingRecord->id]), $existingRecord->employee->user));
                    } else {
                        $schedule = new EmployeeScheduleTeacher([
                            'empl_id' => $resultKeyData,
                            'start_at' => $startAt,
                            'end_at' => $endAt,
                            'dates' => $result,
                            'workdays_count' => $data['totalUserShiftMonthly'][$resultKeyData][$keyDates]
                        ]);


                        if ($schedule->save()) {
                            $schedule->employee->user->notify(new AllocativeSubmissionNotification('Penjadwalan anda telah dibuat oleh ' . $employee->user->name . ', silakan cek pada link berikut ' . route('portal::schedule.submission.show', ['submission' => $schedule->id]), $schedule->employee->user));
                        }
                    }
                    //}
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::error('Error saat menyimpan data: ' . $e->getMessage());
            return false;
        }
    }
}
