<?php

namespace Modules\HRMS\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSchedule;
use Illuminate\Support\Collection;
use Modules\HRMS\Models\EmployeeScanLog;
use Modules\HRMS\Models\EmployeeTeacherScanLog;
use Modules\HRMS\Models\TeacherScanLog;

trait EmployeeScheduleRepository
{
    /**
     * Store newly created resource.
     */
    public function storeEmployeeSchedule(array $data)
    {
        $schedule = new EmployeeSchedule(Arr::only($data, ['empl_id', 'empl_category_id', 'start_at', 'end_at', 'dates', 'workdays_count']));
        if ($schedule->save()) {
            Auth::user()->log('membuat jadwal kerja karyawan ' . $schedule->employee->user->name . ' <strong>[ID: ' . $schedule->id . ']</strong>', EmployeeSchedule::class, $schedule->id);
            return $schedule;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateEmployeeSchedule(EmployeeSchedule $schedule, array $data)
    {
        $schedule->fill(Arr::only($data, ['dates', 'workdays_count']));
        if ($schedule->save()) {
            Auth::user()->log('memperbarui jadwal kerja karyawan ' . $schedule->employee->user->name . ' <strong>[ID: ' . $schedule->id . ']</strong>', EmployeeSchedule::class, $schedule->id);
            return $schedule;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyEmployeeSchedule(EmployeeSchedule $schedule)
    {
        if ($schedule->delete()) {
            Auth::user()->log('menghapus jadwal kerja karyawan ' . $schedule->employee->user->name . ' <strong>[ID: ' . $schedule->id . ']</strong>', EmployeeSchedule::class, $schedule->id);
            return $schedule;
        }
        return false;
    }

    /**
     * add batch schedule.
     */
    public function generateMonthlySchedules($month = null)
    {
        $startYear = $month ? Carbon::parse($month)->startOfYear() : Carbon::now()->startOfYear();
        $endYear = $month ? Carbon::parse($month)->endOfYear() : Carbon::now()->endOfYear();

        $holidays  = CompanyMoment::whereBetween('date', [$startYear, $endYear])->where('is_holiday', 1)
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            });

        $employees = Employee::with('position.position', 'user')->whereHas('position.position', fn($p) => $p->whereIn('type', [PositionTypeEnum::BACKOFFICE]))->get();

        DB::beginTransaction();

        try {
            foreach ($employees as $employee) {
                $currentDate = $startYear->copy();

                while ($currentDate->year == $startYear->year) {
                    $startOfMonth = $currentDate->copy()->startOfMonth();
                    $endOfMonth = $currentDate->copy()->endOfMonth();
                    $scheduleDates = [];
                    $scheduleCount = 0;

                    for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                        $formattedDate = $date->format('Y-m-d');

                        if ($date->isWeekend() || $holidays->contains($formattedDate)) {
                            $scheduleDates[$formattedDate] = [[null, null], [null, null], [null, null]];
                        } else {
                            $shift1 = [$date->format('Y-m-d') . ' 07:00:00', $date->format('Y-m-d') . ' 15:00:00'];
                            $shift2 = [null, null];
                            $shift3 = [null, null];

                            $scheduleDates[$formattedDate] = [$shift1, $shift2, $shift3];
                            $scheduleCount++;
                        }
                    }

                    $schedule = new EmployeeSchedule([
                        'empl_id' => $employee->id,
                        'start_at' => $startOfMonth->format('Y-m-d'),
                        'end_at' => $endOfMonth->format('Y-m-d'),
                        'dates' => $scheduleDates,
                        'workdays_count' => $scheduleCount
                    ]);
                    $schedule->save();
                    $currentDate->addMonth();
                }
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            Log::error('Terjadi kegagalan saat menyimpan jadwal', ['error' => $th->getMessage()]);
            DB::rollBack();
            return false;
        }
    }

    /**
     * iterate schedule.
     */
    public function renderSchedule(Collection $data, $start_at, $end_at)
    {
        // iterate schedule
        $x = $data->pluck('dates')
            ->mapWithKeys(fn($v) => $v)
            ->filter(fn($v, $k) => Carbon::parse($start_at)->lte(Carbon::parse($k)) && Carbon::parse($end_at)->gte(Carbon::parse($k)));
        return $x;
    }

    /**
     * iterate schedule.
     */
    public function iterateSchedule(Collection $data)
    {
        // iterate schedule
        $x = [];
        foreach ($data as $date => $shifts) {
            foreach ($shifts as $key => $shift) {
                if (array_filter($shift)) {
                    $x[$date][] = (object) [
                        'shift' => $key,
                        'in' => Carbon::parse($shift[0])->format('H:i'),
                        'out' => Carbon::parse($shift[1])->format('H:i'),
                    ];
                }
            }
        }
        return $x;
    }

    /**
     * iterate schedule.
     */
    public function countSchedule(Collection $data)
    {
        // count schedule
        return count(collect($this->iterateSchedule($data))->flatten(1));
    }

    public function generatePresences()
    {
        $startYear = Carbon::now()->startOfYear();
        $endPeriod = Carbon::now();

        $holidays  = CompanyMoment::whereBetween('date', [$startYear, $endPeriod])->where('is_holiday', 1)
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            });

        $employees = Employee::with(['user', 'contract' => fn($w) => $w->with('position.position')])
            ->whereHas('contract.position', fn($p) => $p->whereHas('position', fn($d) => $d->where('type', PositionTypeEnum::BACKOFFICE)))->get();

        try {
            foreach ($employees as $employee) {
                $currentDate = $startYear->copy();

                while ($currentDate->year == $startYear->year) {
                    $startOfMonth = $currentDate->copy()->subMonth(1)->startOfMonth()->addDays(20);
                    $endOfMonth = $currentDate->copy()->startOfMonth()->addDays(19);

                    for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                        $formattedDate = $date->format('Y-m-d');

                        if ($date->isWeekend() || $holidays->contains($formattedDate)) {
                            continue;
                        } else {
                            $times = ['08:00:00', '16:00:00'];
                            foreach ($times as $key => $value) {
                                $scan = new EmployeeScanLog([
                                    'empl_id' => $employee->id,
                                    'location' => 1,
                                    'created_at' => $date->format('Y-m-d') . ' ' . $value
                                ]);
                                $scan->save();
                            }
                        }
                    }
                    $currentDate->addMonth();
                }
            }

            return true;
        } catch (\Throwable $th) {
            Log::error('Terjadi kegagalan saat menyimpan presensi', ['error' => $th->getMessage()]);
            return false;
        }
    }

    public function generateTeacherPresences()
    {
        $startYear = Carbon::now()->startOfYear();
        $endPeriod = Carbon::now();

        $holidays  = CompanyMoment::whereBetween('date', [$startYear, $endPeriod])->where('is_holiday', 1)
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            });

        $employees = Employee::with(['user', 'contract' => fn($w) => $w->with('position.position')])
            ->whereHas('contract.position', fn($p) => $p->whereHas('position', fn($d) => $d->where('type', PositionTypeEnum::TEACHER)))->get();

        try {
            foreach ($employees as $employee) {
                $currentDate = $startYear->copy();

                while ($currentDate->year == $startYear->year) {
                    $startOfMonth = $currentDate->copy()->subMonth(1)->startOfMonth()->addDays(20);
                    $endOfMonth = $currentDate->copy()->startOfMonth()->addDays(19);

                    for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                        $formattedDate = $date->format('Y-m-d');

                        if ($date->isWeekend() || $holidays->contains($formattedDate)) {
                            continue;
                        } else {
                            $times = ['08:00:00', '16:00:00'];
                            foreach ($times as $key => $value) {
                                $scan = new EmployeeTeacherScanLog([
                                    'empl_id' => $employee->id,
                                    'location' => 1,
                                    'created_at' => $date->format('Y-m-d') . ' ' . $value
                                ]);
                                $scan->save();
                            }
                        }
                    }
                    $currentDate->addMonth();
                }
            }

            return true;
        } catch (\Throwable $th) {
            Log::error('Terjadi kegagalan saat menyimpan presensi', ['error' => $th->getMessage()]);
            return false;
        }
    }

    public function generatePresencesDaily()
    {
        $today = Carbon::now();
        $holidays = CompanyMoment::where('date', $today->format('Y-m-d'))->where('is_holiday', 1)->get()->pluck('date');
        $employees = Employee::with(['user', 'contract' => fn($w) => $w->with('position.position')])
            ->whereHas('contract.position', fn($p) => $p->whereHas('position', fn($d) => $d->where('type', PositionTypeEnum::BACKOFFICE)))
            ->get();

        DB::beginTransaction();

        try {
            foreach ($employees as $employee) {
                $date = $today;
                if ($date->isWeekend() || $holidays->contains($date->format('Y-m-d'))) {
                    continue;
                } else {
                    $times = ['08:00:00', '16:00:00'];
                    foreach ($times as $key => $value) {
                        $scan = new EmployeeScanLog([
                            'empl_id' => $employee->id,
                            'location' => 1,
                            'created_at' => $date->format('Y-m-d') . ' ' . $value
                        ]);
                        $scan->save();
                    }
                }
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            Log::error('Terjadi kegagalan saat menyimpan presensi', ['error' => $th->getMessage()]);
            DB::rollBack();
            return false;
        }
    }
}
