<?php

namespace Modules\Finance\Http\Controllers\Summary;

use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyOutworkCategory;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\Finance\Http\Requests\Summary\Outwork\StoreRequest;
use Modules\HRMS\Models\EmployeeOutwork;

class OutworkController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        return view('finance::summary.outworks.index', [
            'start_at'    => $start_at,
            'end_at'      => $end_at,
            'departments' => CompanyDepartment::where('grade_id', userGrades())->visible()->with('positions')->get(),
            'employees'   => Employee::with(['user', 'contract.position.position', 'dataRecapitulations' => fn($recap) => $recap->whereType(DataRecapitulationTypeEnum::OUTWORK)->whereStrictPeriodIn($start_at, $end_at)])
                ->withCount(['outworks' => fn($outwork) => $outwork->whereBetween('paidable_at', [$start_at, $end_at])])
                ->whereHas('contract')
                ->whereHas('outworks', fn($outwork) => $outwork->whereBetween('paidable_at', [$start_at, $end_at]))
                ->where('grade_id', userGrades())
                ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
                ->search($request->get('search'))
                ->paginate($request->get('limit', 10))
        ]);
    }

    /**
     * Create resource    
     */
    public function create(Request $request)
    {
        return view('finance::summary.outworks.create', [
            'employee'   => ($employee = Employee::findOrFail($request->get('employee'))),
            'start_at'   => ($start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00')),
            'end_at'     => ($end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59')),
            'categories' => CompanyOutworkCategory::where('grade_id', userGrades())->get(),
            'schedules'  => ($schedules = $employee->schedules()->wherePeriodIn($start_at, $end_at)->pluck('dates')->flatMap(fn($v) => $v)),
            'recap'    => EmployeeDataRecapitulation::whereType(DataRecapitulationTypeEnum::OUTWORK)
            ->where('employee', function($query){
                $query->where('grade_id', userGrades());
            })
            ->find($request->get('edit')),
            'outworks'   => $employee->outworks()->with('category')->whereBetween('paidable_at', [$start_at, $end_at])->get()->each(function ($outwork) use ($schedules) {
                $outwork->dates = $outwork->dates->map(function ($date) use ($schedules) {
                    $date['s'] = Carbon::parse($date['d'] . ' ' . $date['t_s']);
                    $date['e'] = Carbon::parse($date['d'] . ' ' . $date['t_e']);
                    $inside = 0;
                    $outside = $date['s']->diffInMinutes($date['e']);
                    $schedule = $schedules[$date['d']] ?? [];
                    if (count(array_filter(Arr::flatten($schedule)))) {
                        foreach ($schedule as $shift) {
                            $shift = array_map(fn($time) => Carbon::parse($time), $shift);
                            // Klausul 1
                            if ($shift[0]->gte($date['s']) && $shift[0]->lte($date['e']) && $shift[1]->gte($date['s']) && $shift[1]->gte($date['e'])) {
                                $inside += $shift[0]->diffInMinutes($date['e']);
                            }
                            // Klausul 2
                            elseif ($shift[0]->lte($date['s']) && $shift[0]->lte($date['e']) && $shift[1]->gte($date['s']) && $shift[1]->lte($date['e'])) {
                                $inside += $shift[1]->diffInMinutes($date['s']);
                            }
                            // Klausul 5
                            elseif ($shift[0]->lte($date['s']) && $shift[0]->lte($date['e']) && $shift[1]->gte($date['s']) && $shift[1]->gte($date['e'])) {
                                $inside += $date['s']->diffInMinutes($date['e']);
                            }
                        }
                    }
                    $outside -= $inside;
                    return [...$date, ...compact('inside', 'outside', 'schedule')];
                });
                return $outwork;
            })
        ]);
    }

    /**
     * Store resource    
     */
    public function store(StoreRequest $request)
    {
        $employee = Employee::find($request->input('employee'));

        if ($current = EmployeeDataRecapitulation::whereType(DataRecapitulationTypeEnum::OUTWORK)->find($request->get('edit'))) {
            $employee->outworks()->whereIn('id', array_column($current->result->outworks, 'id'))->update([
                'paid_off_at' => null,
                'paid_amount' => null
            ]);
        }

        $recap = $employee->dataRecapitulations()->updateOrCreate(
            [
                'type' => DataRecapitulationTypeEnum::OUTWORK,
                ...$request->transformed()->only('start_at', 'end_at'),
            ],
            $request->transformed()->only('result')
        );

        $now = now();
        foreach ($request->transformed()->input('outworks') as $outwork) {
            $employee->outworks()->find($outwork['id'])->update([
                'paid_off_at' => $now,
                'paid_amount' => array_sum(array_column($outwork['dates'], 'total'))
            ]);
        }
        $request->user()->log(($current ? 'memperbarui' : 'membuat') . ' rekap ' . count($request->input('outworks', [])) . ' kegiatan lain karyawan atas nama ' . $employee->user->name, EmployeeDataRecapitulation::class, $recap->id);

        return redirect()->next()->with('success', 'Rekap total <strong>' . count($request->input('outworks', [])) . '</strong> kegiatan lain karyawan atas nama <strong>' . $employee->user->name . '</strong> berhasi ' . ($current ? 'diperbarui' : 'dibuat') . '!');
    }

    public function destroy(Employee $employee, Request $request)
    {
        $outworks = explode(',', $request->get('outworks'));
        $employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::OUTWORK)->whereIn('id', $outworks)->get()->each(
            fn($recap) => EmployeeOutwork::whereIn('id', array_column($recap->result->outworks, 'id'))->update([
                'paid_off_at' => null,
                'paid_amount' => null
            ])
        );
        $employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::OUTWORK)->whereIn('id', $outworks)->delete();
        $request->user()->log('menghapus rekap ' . count($outworks) . ' kegiatan lain karyawan atas nama ' . $employee->user->name, Employee::class, $employee->id);

        return redirect()->next()->with('success', 'Rekap total <strong>' . count($outworks) . '</strong> kegiatan lain karyawan atas nama <strong>' . $employee->user->name . '</strong> berhasi dihapus!');
    }
}
