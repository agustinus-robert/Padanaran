<?php

namespace Modules\Finance\Http\Controllers\Tax;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\TaxCategoryEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\Docs\Models\Document;
use Modules\HRMS\Enums\TaxTypeEnum;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeTax;
use Modules\Finance\Http\Requests\Tax\Pph\StoreRequest;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Models\EmployeeDataRecapitulation;

class PphController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        return view('finance::tax.pph.index', [
            'start_at'    => $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00'),
            'end_at'      => $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59'),
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'types'       => collect(TaxTypeEnum::cases()),
            'employees' => Employee::with([
                'salaries' => fn($salary) => $salary->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d')),
                'taxs' => fn($tax) => $tax->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d')),
                'user',
                'position.position'
            ])->whenWithTrashed($request->get('trashed'))->where('grade_id', userGrades())->paginate($request->get('limit', 10)),
        ]);
    }


    public function create(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at   = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');
        $employee = Employee::with([
            'salaries' => fn($salary) => $salary->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d')),
            'user',
            'position.position'
        ])->find($request->get('tax'));

        $ters = getTERCategory($employee->user->getMeta('profile_mariage'), $employee->user->getMeta('profile_child'), $employee->user->getMeta('profile_sex'));
        
        return view('finance::tax.pph.create', [
            'start_at'   => $start_at,
            'end_at'     => $end_at,
            'types'      => [TaxTypeEnum::MONTHLY],
            'components' => CompanySalarySlipComponent::get(),
            'categories' => collect(TaxCategoryEnum::cases()),
            'employee'   => $employee,
            'ters'       => $ters,
        ]);
    }

    public function store(StoreRequest $request)
    {
        $pph = new EmployeeTax($request->transformed()->toArray());

        if ($pph->save()) {
            if ($request->input('as_recap')) {
                if ($this->recap($pph)) {
                    return redirect()->next()->with('success', 'Rekap PPh 21 <strong>' . $pph->employee->user->name . '</strong> berhasil dibuat.');
                }
                return redirect()->next()->with('danger', 'Terjadi kegagalan membuat rekap PPh 21 <strong>' . $pph->employee->user->name . '</strong>.');
            }
            return redirect()->next()->with('success', 'PPh 21 <strong>' . $pph->employee->user->name . '</strong> berhasil dibuat.');
        }
        return redirect()->fail();
    }

    public function show(Document $employeetax)
    {
        return $employeetax->show();
    }

    public function release(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at   = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        if (EmployeeTax::where('type', TaxTypeEnum::MONTHLY)->whenPeriod($start_at, $end_at)->update(['released_at' => now()])) {
            return redirect()->next()->with('success', 'PPh 21 berhasil diterbitkan.');
        }
        return redirect()->fail();
    }

    public function recap($pph)
    {
        $recap = new EmployeeDataRecapitulation([
            'empl_id' => $pph->empl_id,
            'type' => DataRecapitulationTypeEnum::PPH,
            'start_at' => $pph->start_at->format('Y-m-d'),
            'end_at' => $pph->end_at->format('Y-m-d'),
            'result' => [
                'id' => $pph->id,
                'type' => $pph->type,
                'bruto' => $pph->meta?->pkp,
                'amount' => $pph->meta?->amount,
                'pph' => $pph->meta?->pphtotal,
            ]
        ]);
        if ($recap->save()) {
            return true;
        }
        return false;
    }

    public function summary(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $taxs = EmployeeTax::with([
            'employee' => fn($w) => $w->with('user', 'contract', 'position.position')
        ])->whereType(TaxTypeEnum::MONTHLY)->whereStartAt($start_at)->whereEndAt($end_at)->get();

        $sheet['Penghitungan PPh 21'] = [
            'columns' => [
                'number' => 'No',
                'name' => 'Nama Karyawan',
                'department' => 'Departemen',
                'position' => 'Posisi',
                'start_at' => 'Periode mulai',
                'end_at' => 'Periode selesai',
                'type' => 'Kategori',
                'bruto_month' => 'Bruto 1 bulan',
                'bruto_year' => 'Bruto 1 tahun',
                'reduction_month' => 'Potongan 1 bulan',
                'reduction_year' => 'Potongan 1 tahun',
                'charge' => 'Biaya jabatan',
                'netto' => 'Pendapatan bersih',
                'ptkp' => 'PTKP',
                'pkp' => 'PKP',
                'pph1' => 'PPh lapis pertama',
                'pph2' => 'PPh lapis kedua',
                'pph3' => 'PPh lapis ketiga',
                'pph4' => 'PPh lapis keempat',
                'pph5' => 'PPh lapis kelima',
                'pph_total' => 'Total PPh',
            ],
            'data' => $taxs->map(function ($item, $index) {
                return [
                    'number'        => $index + 1 ?? '',
                    'name'          => $item->employee->user->name ?? 0,
                    'department'    => $item->employee->position->position->department->name ?? 0,
                    'position'      => $item->employee->position->position->name ?? 0,
                    'start_at'      => Carbon::parse($item->start_at)->isoFormat('LL') ?? 0,
                    'end_at'        => Carbon::parse($item->end_at)->isoFormat('LL') ?? 0,
                    'type'          => $item->type->label() ?? 0,
                    'bruto_month'   => (int) ($item->meta?->income->totalmonth ?? 0),
                    'bruto_year'    => (int) ($item->meta?->income->totalyear ?? 0),
                    'reduction_month' => (int) ($item->meta?->reduction->totalmonth ?? 0),
                    'reduction_year'  => (int) ($item->meta?->reduction->totalyear ?? 0),
                    'charge'        => (int) ($item->meta?->charge ?? 0),
                    'netto'         => (int) ($item->meta?->netto ?? 0),
                    'ptkp'          => (int) ($item->meta?->ptkp ?? 0),
                    'pkp'           => (int) ($item->meta?->pkp ?? 0),
                    'pph1'          => (int) ($item->meta?->pph1 ?? 0),
                    'pph2'          => (int) ($item->meta?->pph2 ?? 0),
                    'pph3'          => (int) ($item->meta?->pph3 ?? 0),
                    'pph4'          => (int) ($item->meta?->pph4 ?? 0),
                    'pph5'          => (int) ($item->meta?->pph5 ?? 0),
                    'pph_total'     => (int) ($item->meta?->pphtotal ?? 0),
                ];
            }),
        ];

        return response()->json([
            'title'     => ($title = 'Rekap PPh21 periode ' . date('Y-m-d', strtotime($start_at)) . ' - ' . date('Y-m-d', strtotime($end_at))),
            'subtitle'  => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file'      => Str::slug($title . '-' . time()),
            'sheets'    => $sheet
        ]);
    }
}
