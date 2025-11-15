<?php

namespace Modules\HRMS\Http\Controllers\API;

use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;

class AttendaceReportController extends Controller
{

    /**
     * Fetch all empls.
     */
    public function index(Request $request)
    {
        if ($request->header('apikey') == 'vAWGg6KrAGsxuz9') {
            $start_at = Carbon::parse($request->get('start_at', date('Y-01-01')) . ' 00:00:00');
            $end_at = Carbon::parse($request->get('end_at', date('Y-12-31')) . ' 23:59:59');

            $employee = Employee::with([
                'scanlogs' => fn($scanlog) => $scanlog->whereBetween('created_at', [$start_at, $end_at]),
                'schedules' => fn($schedule) => $schedule->wherePeriodIn($start_at, $end_at)
            ])->find($request->get('empl'));

            $scanlogs = $employee->scanlogs->groupBy(fn($log) => $log->created_at->format('Y-m-d'));
            $schedules = $employee->schedules->each(function ($schedule) use ($scanlogs) {
                $schedule->entries = $schedule->getEntryLogs($scanlogs);
                return $schedule;
            });

            $days = $employee->schedules
                ->map(fn($schedule) => $schedule->dates
                    ->filter(fn($times, $date) => $start_at->lte($date) && $end_at->gte($date))
                    ->map(fn($date) => count(array_filter($date, fn($times) => count(array_filter($times)) == 1)))
                    ->sum())
                ->sum();

            $presences = $employee->schedules
                ->map(fn($schedule) => count(array_filter(Arr::flatten($schedule->entries, 1), fn($entry) => isset($entry->ontime) && !is_null($entry->ontime))))
                ->sum();

            $ontime = $employee->schedules
                ->map(fn($schedule) => count(array_filter(Arr::flatten($schedule->entries, 1), fn($entry) => $entry->ontime === true)))
                ->sum();

            $late = $employee->schedules
                ->map(fn($schedule) => count(array_filter(Arr::flatten($schedule->entries, 1), fn($entry) => $entry->ontime === false)))
                ->sum();

            return response()->success([
                'message' => 'Berikut adalah informasi karyawan ' . $employee->user->name . ' yang terekam di HRMS.',
                'days' => $days,
                'presences' => $presences,
                'ontime' => $ontime,
                'late' => $late,
            ]);
        }
    }
}
