<?php

namespace Modules\Finance\Http\Controllers\Summary;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\Finance\Http\Requests\Summary\Deduction\StoreRequest;
use Modules\HRMS\Models\EmployeeDeduction;

class DeductionController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');
//        $employee = Employee::findOrFail($request->get('employee'));

        // dd('ok');
        return view('finance::summary.deductions.index', [
            'start_at'    => $start_at,
            'end_at'      => $end_at,
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'deductions' => $employee->deductions()->with('component')->whereBetween('created_at', [$start_at, $end_at])->get(),
            'employees'   => Employee::with(['user', 'contract.position.position', 'dataRecapitulations' => fn($recap) => $recap->whereType(DataRecapitulationTypeEnum::DEDUCTION)->whereStrictPeriodIn($start_at, $end_at)])
                ->whereHas('contract')
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
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');
        $employee = Employee::findOrFail($request->get('employee'));

        return view('finance::summary.deductions.create', [
            'employee'   => $employee,
            'start_at'   => $start_at,
            'end_at'     => $end_at,
            'recap'      => EmployeeDataRecapitulation::whereType(DataRecapitulationTypeEnum::DEDUCTION)->find($request->get('edit')),
            'deductions' => $employee->deductions()->with('component')->whereBetween('created_at', [$start_at, $end_at])->get()
        ]);
    }

    /**
     * Store resource    
     */
    public function store(StoreRequest $request)
    {
        $employee = Employee::find($request->input('employee'));

        $recap = $employee->dataRecapitulations()->create($request->transformed()->toArray());

        $now = now();
        foreach ($request->transformed()->input('deductions') as $key => $deduction) {
            $employee->deductions()->find($deduction['deduction_id'])->update([
                'paid_at' => $now,
            ]);
        }

        $request->user()->log('membuat rekap Reward karyawan atas nama ' . $employee->user->name, EmployeeDataRecapitulation::class, $recap->id);

        return redirect()->next()->with('success', 'Rekap potongan karyawan atas nama <strong>' . $employee->user->name . '</strong> berhasi dibuat!');
    }

    public function destroy(EmployeeDataRecapitulation $deduction, Request $request)
    {
        foreach ($deduction->result->deductions as $key => $value) {
            $data[$key] = EmployeeDeduction::find($value->deduction_id);
            $data[$key]->update(['paid_at' => null]);
        }
        $deduction->delete();
        return redirect()->next()->with('success', 'Rekap <strong>' . count($deduction->result->deductions) . '</strong> potongan karyawan atas nama <strong>' . $deduction->employee->user->name . '</strong> berhasi dihapus!');
    }

    public function summary(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $deductions = EmployeeDataRecapitulation::where('type', DataRecapitulationTypeEnum::DEDUCTION)->with([
            'employee' => fn($w) => $w->with('user', 'contract', 'position.position')
        ])->whereDate('start_at', $start_at)->whereDate('end_at', $end_at)->get();

        $sheet['Penghitungan Potongan'] = [
            'columns' => [
                'number'     => 'No',
                'name'       => 'Nama Karyawan',
                'department' => 'Departemen',
                'position'   => 'Posisi',
                'start_at'   => 'Periode mulai',
                'end_at'     => 'Periode selesai',
                'type'       => 'Kategori',
                'nominal'    => 'Nominal',
            ],
            'data' => $deductions->map(function ($item, $index) {
                return [
                    'number'     => $index + 1 ?? '',
                    'name'       => $item->employee->user->name ?? 0,
                    'department' => $item->employee->position->position->department->name ?? 0,
                    'position'   => $item->employee->position->position->name ?? 0,
                    'start_at'   => Carbon::parse($item->start_at)->isoFormat('LL') ?? 0,
                    'end_at'     => Carbon::parse($item->end_at)->isoFormat('LL') ?? 0,
                    'type'       => $item->type->label() ?? 0,
                    'nominal'    => (int)($item->result->amount_total ?? 0),
                ];
            }),
        ];

        return response()->json([
            'title' => ($title = 'Rekap potongan periode ' . date('Y-m-d', strtotime($start_at)) . ' - ' . date('Y-m-d', strtotime($end_at))),
            'subtitle' => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file' => Str::slug($title . '-' . time()),
            'sheets' => $sheet
        ]);
    }
}
