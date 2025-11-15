<?php

namespace Modules\HRMS\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\HRMS\Models\EmployeeScheduleSubmission;
use Modules\HRMS\Models\EmployeeSchedule;
use Modules\Core\Models\CompanyApprovable;
use Modules\Portal\Notifications\Schedule\Submission\AllocativeSubmissionNotification;

trait EmployeeCollectiveScheduleRepository
{
    /**
     * Store newly created resource.
     */
    public function storeEmployeeSchedule(array $data, $employee)
    {
        $startAt = Carbon::createFromFormat('Y-m', $data['month'])->startOfMonth()->format('Y-m-d');
        $endAt = Carbon::createFromFormat('Y-m', $data['month'])->endOfMonth()->format('Y-m-d');
        $dataSh = [];

        DB::beginTransaction();

        try {

            $shiftDayCount = [];
            $shiftDataCountTemp = 0;
            foreach ($data['data'] as $resultKeyData => $resultValueData) {
                $shiftDataCountTemp = 0;
                foreach ($resultValueData as $shiftData) {

                    foreach($shiftData as $shitingDatas){
                        if ($shitingDatas[0] !== null || $shitingDatas[1] !== null) {
                            $shiftDataCountTemp++;
                        }
                    }
                }

                $shiftDayCount[$resultKeyData] = $shiftDataCountTemp;
            }

            foreach ($data['data'] as $resultKeyData => $resultValueData) {

                $arrData = json_encode($resultValueData);

                $existingRecord = EmployeeScheduleSubmission::where(['start_at' => $startAt, 'end_at' => $endAt, 'empl_id' => $resultKeyData])->first();

                if ($existingRecord) {


                    if(json_encode($existingRecord->dates) !== $arrData){
                        if(!empty($existingRecord->approved_at)){
                            $updateApprove = CompanyApprovable::where('modelable_id', $existingRecord->id)->first();
                            if($updateApprove){
                                $updateApprove->level = 1;
                                $updateApprove->result = 3;
                                $updateApprove->reason = 'Pengajuan Baru';
                                $updateApprove->save();

                                $arrApv['dates'] = $resultValueData;
                                $arrApv['workdays_count'] = $shiftDayCount[$resultKeyData];
                                $arrApv['updated_by'] = null;
                                $existingRecord->update($arrApv);

                                $existingRecord->employee->user->notify(new AllocativeSubmissionNotification('Penjadwalan anda telah disetujui oleh ' . $existingRecord->creator->user->name . ', silakan cek pada link berikut <a href="' . route('portal::schedule.submission.show', ['submission' => $existingRecord->id]).">Klik Disini</a>", $existingRecord->employee->user));
                            }
                        }
                    } else {
                        $arrUdt['dates'] = $resultValueData;
                        $arrUdt['workdays_count'] = $shiftDayCount[$resultKeyData];

                        $existingRecord->update($arrUdt);

                        foreach (config('modules.core.features.services.schedules.approvable_steps', []) as $model) {
                            if ($model['type'] == 'parent_position_level') {
                                if ($position = $employee->position->position->parents->firstWhere('level.value', $model['value'])?->employeePositions()->active()->first()) {

                                        $position->employee->user->notify(new AllocativeSubmissionNotification(
                                             'Anda mendapatkan notifikasi pengajuan jadwal dari ' . $employee->user->name .
                                            ', silakan cek pada link berikut: ' . route('portal::schedule.submission.show', ['submission' => $existingRecord->id]), $position->employee->user));

                                }
                            }
                        }

                        $existingRecord->employee->user->notify(new AllocativeSubmissionNotification(
                            'Penjadwalan anda telah diajukan kembali oleh ' . $employee->user->name .
                            ', silakan cek pada link berikut: ' . route('portal::schedule.submission.show', ['submission' => $existingRecord->id]),
                            $existingRecord->employee->user
                        ));
                    }





                    // $existingRecord->employee->user->notify(new AllocativeSubmissionNotification('Penjadwalan Anda telah disetujui oleh ' . $existingRecord->creator->user->name . ', silakan cek pada link berikut ' . route('portal::schedule.submission.show', ['submission' => $existingRecord->id]), $existingRecord->employee->user));
                //} else if ($realRecord && $existingRecord) {   continue; }
                } else {
                    $schedule = new EmployeeScheduleSubmission([
                        'empl_id' => $resultKeyData,
                        'start_at' => $startAt,
                        'end_at' => $endAt,
                        'dates' => $resultValueData,
                        'workdays_count' => $shiftDayCount[$resultKeyData]
                    ]);


                    if ($schedule->save()) {
                        foreach (config('modules.core.features.services.schedules.approvable_steps', []) as $model) {
                            if ($model['type'] == 'parent_position_level') {
                                if ($position = $employee->position->position->parents->firstWhere('level.value', $model['value'])?->employeePositions()->active()->first()) {

                                    $schedule->createApprovable($position);

                                    $position->employee->user->notify(new AllocativeSubmissionNotification('Anda mendapatkan notifikasi pengajuan dari ' . $employee->user->name . ', silakan cek pada link berikut ' . route('portal::schedule.submission.show', ['submission' => $schedule->id]), $position->employee->user));
                                }
                            }
                        }

                        $schedule->employee->user->notify(new AllocativeSubmissionNotification('Penjadwalan anda telah diajukan oleh ' . $employee->user->name . ', silakan cek pada link berikut ' . route('portal::schedule.submission.show', ['submission' => $schedule->id]), $schedule->employee->user));
                    }
                }
            }



            //new SubmissionNotification($schedule);

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
