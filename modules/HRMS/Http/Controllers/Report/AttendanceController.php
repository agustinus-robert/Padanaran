<?php

namespace Modules\HRMS\Http\Controllers\Report;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeScanLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $employeeCollections = Employee::where('grade_id', userGrades())->whereHas('contract')->with([
            'user.meta',
            'contract.position.position',
            'schedules' => fn($schedule) => $schedule->wherePeriodIn($start_at, $end_at)
        ])->get();

        // ->whereDoesntHave('contract.position.position', function ($q) {
        //     $q->where('type', PositionTypeEnum::GURU->value);
        // })

        $employeeCollections->each(function ($employee) use ($start_at, $end_at) {
            $employee->scanlogs = $scanlogs = EmployeeScanLog::where('empl_id', $employee->id)
                ->whereHas('employee', function($query){
                    $query->where('grade_id', userGrades());
                })
                ->where('created_at', '>=', $start_at)
                ->where('created_at', '<=', $end_at)
                ->get()
                ->groupBy(fn($log) => $log->created_at->format('Y-m-d'));

            $employee->schedules->each(function ($schedule) use ($scanlogs) {
                $schedule->entries = $schedule->getEntryLogs($scanlogs);
            });
        });

        $totalGroup = count($employeeCollections);
        $perPage    = $request->get('limit', 10);
        $page       = Paginator::resolveCurrentPage('page');

        $employees = new LengthAwarePaginator($employeeCollections->forPage($page, $perPage), $totalGroup, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);


        return view('hrms::report.attendances.index', [
            ...compact('start_at', 'end_at'),
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'employees' => $employees
        ]);
    }

    /**
     * Summary
     */
    public function summary(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $period = $start_at->isSameDay($end_at) ? $end_at->isoFormat('DD MMM YYYY') : $start_at->isoFormat('DD MMM') . ' s.d. ' . $end_at->isoFormat('DD MMM YYYY');

        $employees = Employee::where('grade_id', userGrades())->whereHas('contract')->with([
            'user.meta',
            'contract.position.position',
            'schedules' => fn($schedule) => $schedule->wherePeriodIn($start_at, $end_at)
        ])->get();

        $employees->each(function ($employee) use ($start_at, $end_at) {
            $employee->scanlogs = EmployeeScanLog::where('empl_id', $employee->id)
                ->whereHas('employee', function($query){
                    $query->where('grade_id', userGrades());
                })
                ->where('created_at', '>=', $start_at)
                ->where('created_at', '<=', $end_at)
                ->get();

            $scanlogs = $employee->scanlogs->groupBy(fn($log) => $log->created_at->format('Y-m-d'));

            $schedules = $employee->schedules->each(function ($schedule) use ($scanlogs) {
                $schedule->entries = $schedule->getEntryLogs($scanlogs);
                return $schedule;
            });
        });

        $periods = collect(CarbonPeriod::create($start_at, $end_at));

        foreach (WorkLocationEnum::cases() as $loc) {
            $locations[$loc->value] = $loc->name;
        }

        $sheets['Rekapitulasi keseluruhan'] = [
            'columns' => [
                'number' => 'No',
                'name' => 'Nama',
                'date' => 'Tanggal',
                'check_in' => 'Jam masuk',
                'check_out' => 'Jam pulang',
                'location' => 'Lokasi',
                'summary' => 'Hasil',
                'move' => 'Pindah kantor',
                'description' => 'Keterangan',
                'total_hours' => 'Total jam kerja',
                'fulfilled' => 'Memenuhi jam kerja',
            ],
            'data' => $employees->map(function ($employee) use ($periods, $locations) {
                $entries = $employee->schedules->pluck('entries')->flatten();
                return $periods->map(function ($date, $index) use ($employee, $entries, $locations) {
                    $entry = $entries->firstWhere('date', $date->format('Y-m-d'));
                    return [
                        'number' => $index + 1,
                        'name' => $employee->user->name,
                        'date' => $date->format('Y-m-d'),
                        'check_in' => $entry?->in?->format('H:i:s') ?? '',
                        'check_out' => $entry?->out?->format('H:i:s') ?? '',
                        'location' => isset($entry->location[0]) ? $locations[($entry->location[1] ?? $entry->location[0])] : '',
                        'summary' => is_null($entry?->ontime) ? '' : ($entry?->ontime === true ? 'On Time' : 'Terlambat'),
                        'move' => $entry?->location ? (count(array_unique($entry?->location ?? [])) == 1 ? 'Tidak' : 'Ya') : '',
                        'description' => '',
                        'total_hours' => $entry?->in ? ($fill = isset($entry->in) ? (isset($entry->out) ? $entry->in->diffInHours($entry->out) : $entry->in->diffInHours($entry?->schedule[1])) : 0) : '',
                        'fulfilled' => isset($fill) ? ($fill >= $entry->schedule[0]->diffInHours($entry->schedule[1]) ? 'Ya' : 'Tidak') : ''
                    ];
                });
            })->flatten(1),
        ];

        $sheets['Data scanlog'] = [
            'columns' => [
                'number' => 'No',
                'name' => 'Nama',
                'date' => 'Tanggal',
                'scan' => 'Lokasi',
                'location' => 'Lokasi',
            ],
            'data' => $employees->map(fn($employee) => $employee->scanlogs->map(fn($scanlog, $index) => [
                'number' => $index + 1,
                'name' => $employee->user->name,
                'date' => $scanlog->created_at->format('Y-m-d'),
                'scan' => $scanlog->created_at->format('H:i:s'),
                'location' => isset($scanlog->latlong) && is_array($scanlog->latlong) ?  implode(',', $scanlog->latlong) : [],
                'ip' => $scanlog->ip,
            ]))->flatten(1)
        ];

        return response()->json([
            'title' => ($title = 'Rekap kehadiran karyawan periode ' . $period),
            'subtitle' => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file' => Str::slug($title . '-' . time()),
            'sheets' => $sheets
        ]);
    }
}
