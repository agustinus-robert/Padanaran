<?php

namespace Modules\HRMS\Http\Controllers\Report;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyVacationCategory;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\HRMS\Models\Employee;

class VacationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Employee::class);

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $employees = Employee::where('grade_id', userGrades())->with('user.meta')
            ->withWhereHas('contracts', fn($contracts) => $contracts->with('position.position')->whereActivePeriod($start_at, $end_at))
            ->with([
                'vacations' => fn($vacations) => $vacations->with('quota', 'approvables')->whereExtractedDatesBetween($start_at, $end_at)
            ])
            // ->whereDoesntHave('contract.position.position', function ($q) {
            //     $q->where('type', PositionTypeEnum::GURU->value);
            // })
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));

        return view('hrms::report.vacations.index', [
            ...compact('start_at', 'end_at'),
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'categories' => CompanyVacationCategory::all(),
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

        $employees = Employee::with('user.meta')
            ->where('grade_id', userGrades())
            ->withWhereHas('contracts', fn($contracts) => $contracts->with('position.position')->whereActivePeriod($start_at, $end_at))
            ->with([
                'vacations' => fn($vacations) => $vacations->with('quota', 'approvables')->whereExtractedDatesBetween($start_at, $end_at)
            ])->get();

        $categories = CompanyVacationCategory::all();

        $sheets['Rekapitulasi keseluruhan'] = [
            'columns' => array_merge([
                'number' => 'No',
                'name' => 'Nama'
            ], $categories->mapWithKeys(fn($c) => ['vacation_' . $c->id => $c->name])->toArray()),
            'data' => $employees->map(function ($empl, $index) use ($end_at, $categories) {
                $categories->each(function ($c) use ($empl, &$vacations, $end_at) {
                    $vacations['vacation_' . $c->id] = $empl->vacations
                        ->filter(fn($v) => $v->hasAllApprovableResultIn('APPROVE') && $v->quota->ctg_id == $c->id)
                        ->pluck('dates.*.d')
                        ->unique()
                        ->flatten()
                        ->filter(fn($date) => $end_at->gte($date))
                        ->count();
                });
                return array_merge([
                    'number' => $index + 1,
                    'name' => $empl->user->name,
                ], $vacations);
            })
        ];

        return response()->json([
            'title' => ($title = 'Rekap cuti karyawan periode ' . $period),
            'subtitle' => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file' => Str::slug($title . '-' . time()),
            'sheets' => $sheets
        ]);
    }
}
