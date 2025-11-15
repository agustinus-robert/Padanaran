<?php

namespace Modules\Finance\Http\Controllers\Summary;

use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Enums\PaymentMethodEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\Finance\Http\Requests\Summary\Additional\StoreRequest;
use Modules\HRMS\Enums\AdditionalTypeEnum;
use Modules\HRMS\Models\EmployeeOvertimeAddional;

class AdditionalController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        return view('finance::summary.additional.index', [
            'start_at'    => $start_at,
            'end_at'      => $end_at,
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'employees'   => Employee::with(['user', 'contract.position.position', 'dataRecapitulations' => fn($recap) => $recap->whereType(DataRecapitulationTypeEnum::ADDITIONAL)->whereStrictPeriodIn($start_at, $end_at)])
                ->withCount(['additional_overtimes' => fn($addt) => $addt->whereMethod(PaymentMethodEnum::PAYROLL)->whereBetween('paidable_at', [$start_at, $end_at])])
                ->whereHas('contract')
                ->whereHas('additional_overtimes', fn($addt) => $addt->whereMethod(PaymentMethodEnum::PAYROLL)->whereBetween('paidable_at', [$start_at, $end_at]))
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
        $employee = Employee::findOrFail($request->get('employee'));
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');
        $schedules = $employee->schedules()->wherePeriodIn($start_at, $end_at)->pluck('dates')->flatMap(fn($v) => $v);

        $data = $employee->additional_overtimes()->whereMethod(PaymentMethodEnum::PAYROLL)->whereBetween('paidable_at', [$start_at, $end_at])->get()->each(function ($addt) use ($employee, $schedules) {
            $addt->dates = $addt->dates->map(function ($date) use ($addt, $employee, $schedules) {
                $param = collect(config('modules.finance.features.additional_overtime.options'))->where('id', $addt['type']);
                $pos = $employee->position->position_id;
                $date['s'] = Carbon::parse($date['d'] . ' ' . $date['t_s']);
                $date['e'] = Carbon::parse($date['d'] . ' ' . $date['t_e']);
                $minutes = $date['s']->diffInMinutes($date['e']);
                $schedule = $schedules[$date['d']] ?? [];
                $inside = 0;
                $outside = $date['s']->diffInMinutes($date['e']);
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
                        // Klausul 3
                        elseif ($shift[0]->lte($date['s']) && $shift[0]->lte($date['e']) && $shift[1]->gte($date['s']) && $shift[1]->gte($date['e'])) {
                            $inside += $date['s']->diffInMinutes($date['e']);
                        }
                    }
                }
                $outside -= $inside;
                list($meal, $in, $out, $load) = array(0, 0, 0, 0);
                if ($param) {
                    switch ($param->first()['id']) {
                        case AdditionalTypeEnum::ADDITIONAL:
                            $meal = isset($date['t_m']) ? (int)$employee->{$param->first()['meal']['value']}() : 0;
                            $in = $out = in_array($pos, $param->first()['rate']['except']) ? (int)$param->first()['rate']['value'][1] : (int)$employee->{$param->first()['rate']['value'][0]}();
                            $load = $transport = 0;
                            break;
                        case AdditionalTypeEnum::FEASTDAY:
                            $meal = (int)$employee->{$param->first()['meal']['value']}();
                            $transport = (int)$employee->{$param->first()['transport']['value']}();
                            $in = $out = $employee->{$param->first()['rate']['value']}();
                            $load = 0;
                            break;
                        case AdditionalTypeEnum::VENUE:
                            $meal       = $inside > $outside ? (int)$param->first()['meal']['value'][0] : (int)$param->first()['meal']['value'][1];
                            $transport  = 0;
                            $load       = (int)$param->first()['load']['value'];
                            $in         = (int)$param->first()['rate']['value'][0];
                            $out        = (int)$param->first()['rate']['value'][1];
                            break;
                        default:
                            $meal = $in = $out = $load = $transport = 0;
                    }
                    $rate = compact('in', 'out', 'load', 'transport', 'meal');
                }

                return [...$date, ...compact('minutes', 'inside', 'outside', 'rate')];
            });
            return $addt;
        });

        return view('finance::summary.additional.create', [
            'employee'    => $employee,
            'start_at'    => $start_at,
            'end_at'      => $end_at,
            'recap'       => EmployeeDataRecapitulation::whereType(DataRecapitulationTypeEnum::ADDITIONAL)->find($request->get('edit')),
            'config'      => collect(config('modules.finance.features.additional_overtime.options')),
            'additionals' => $data
        ]);
    }

    /**
     * Store resource    
     */
    public function store(StoreRequest $request)
    {
        $employee = Employee::find($request->input('employee'));

        if ($current = EmployeeDataRecapitulation::whereType(DataRecapitulationTypeEnum::ADDITIONAL)->find($request->get('edit'))) {
            $employee->additional_overtimes()->whereIn('id', array_column($current->result->additionals, 'id'))->update([
                'paid_off_at' => null,
                'paid_amount' => null
            ]);
        }

        $recap = $employee->dataRecapitulations()->updateOrCreate(
            [
                'type' => DataRecapitulationTypeEnum::ADDITIONAL,
                ...$request->transformed()->only('start_at', 'end_at'),
            ],
            $request->transformed()->only('result')
        );

        $now = now();
        foreach ($request->transformed()->input('additionals') as $additional) {
            $employee->additional_overtimes()->find($additional['id'])->update([
                'paid_off_at' => cmp_cutoff(1),
                'paid_amount' => array_sum(array_column($additional['dates'], 'total'))
            ]);
        }

        $request->user()->log(($current ? 'memperbarui' : 'membuat') . ' rekap ' . count($request->input('additionals', [])) . ' lembur karyawan atas nama ' . $employee->user->name, EmployeeDataRecapitulation::class, $recap->id);

        return redirect()->next()->with('success', 'Rekap total <strong>' . count($request->input('additionals', [])) . '</strong> lembur karyawan atas nama <strong>' . $employee->user->name . '</strong> berhasi ' . ($current ? 'diperbarui' : 'dibuat') . '!');
    }

    public function destroy(Employee $employee, Request $request)
    {
        $additionals = explode(',', $request->get('additionals'));

        $employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::ADDITIONAL)->whereIn('id', $additionals)->get()->each(
            fn($recap) => EmployeeOvertimeAddional::whereIn('id', array_column($recap->result->additionals, 'id'))->update([
                'paid_off_at' => null,
                'paid_amount' => null
            ])
        );

        $employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::ADDITIONAL)->whereIn('id', $additionals)->delete();

        $request->user()->log('menghapus rekap ' . count($additionals) . ' lembur karyawan atas nama ' . $employee->user->name, Employee::class, $employee->id);

        return redirect()->next()->with('success', 'Rekap total <strong>' . count($additionals) . '</strong> lembur karyawan atas nama <strong>' . $employee->user->name . '</strong> berhasi dihapus!');
    }
}
