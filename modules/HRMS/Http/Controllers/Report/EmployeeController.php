<?php

namespace Modules\HRMS\Http\Controllers\Report;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Account\Enums\ReligionEnum;
use Modules\Core\Enums\VacationTypeEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyLeaveCategory;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Employee::class);

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $employees = Employee::with('user.meta')
            ->withWhereHas(
                'contracts',
                fn ($contracts) => $contracts->with('positions.position.department')->whereActivePeriod($start_at, $end_at)
            )
            ->where('grade_id', userGrades())
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));

        return view('hrms::report.employees.index', [
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

        $employees = Employee::where('grade_id', userGrades())->whereHas('contracts', fn ($contract) => $contract->whereActivePeriod($start_at, $end_at))
            ->with([
                'user.meta',
                'contracts' => fn ($c) => $c->with('contract', 'positions.position.department'),
                'leaves' => fn ($leave) => $leave->with('category')->whereYear('created_at', $start_at->format('Y')),
                'vacationQuotas' => fn ($quota) => $quota->with('vacations', 'category')->whereYear('start_at', $start_at->format('Y'))
            ])
            ->get();

        $json['Daftar karyawan'] = [
            'columns' => ['number' => 'No.', 'name' => 'Nama', 'pob' => 'Tempat Lahir', 'dob' => 'Tanggal Lahir', 'age' => 'Usia', 'nik' => 'Nomor Induk Kependudukan', 'nip' => 'Nomor Induk Pegawai', 'position' => 'Jabatan', 'department' => 'Departemen', 'joined_at' => 'Tgl Masuk Kerja', 'worktime_year' => 'Masa Kerja;Tahun', 'worktime_month' => 'Masa Kerja;Bulan', 'worktime_day' => 'Masa Kerja;Hari', 'contract_end_at' => 'Tanggal Berakhir Kontrak', 'contract_category' => 'Status Kontrak', 'status' => 'Status Karyawan', 'exited_at' => 'Tgl keluar', 'religion' => 'Agama', 'address' => 'Alamat', 'phone' => 'Ponsel', 'email' => 'Email', 'study' => 'Pendidikan Terakhir', 'contact' => 'Kontak Darurat', 'mother' => 'Nama Ibu'],
            'data' => $employees->map(function ($employee, $index) use ($start_at, $end_at) {
                $contract = $employee->contracts->filter(
                    fn ($contract) => is_null($contract->end_at) || (($start_at->gte($contract->start_at) || $end_at->gte($contract->start_at)) && (($start_at->lte($contract->end_at) || $end_at->lte($contract->end_at))))
                )->first();
                $worktime = $employee->joined_at->diff();
                $religions = ReligionEnum::cases();
                return $contract ? [
                    'number' => $index + 1,
                    'name' => $employee->user->name,
                    'pob' => $employee->user->getMeta('profile_pob'),
                    'dob' => $employee->user->getMeta('profile_dob'),
                    'age' => Carbon::parse($employee->user->getMeta('profile_dob'))->age,
                    'nik' => $employee->user->getMeta('profile_nik'),
                    'nip' => $employee->kd,
                    'position' => $contract->positions->last()?->position->name ?? '',
                    'department' => $contract->positions->last()?->position->department->name ?? '',
                    'joined_at' => $employee->joined_at->format('Y-m-d'),
                    'worktime_year' => $worktime->y,
                    'worktime_month' => $worktime->m,
                    'worktime_day' => $worktime->d,
                    'contract_end_at' => $contract->end_at?->format('Y-m-d'),
                    'contract_category' => strtoupper($contract->contract->kd),
                    'status' => $contract->end_at?->lte($end_at) ? 'Keluar' : ($contract->start_at->between($start_at, $end_at) && $employee->contracts->count() == 1 ? 'Baru' : 'Aktif'),
                    'exited_at' => $employee->exited_at?->format('Y-m-d'),
                    'religion' => $employee->user->getMeta('profile_religion') ? $religions[$employee->user->getMeta('profile_religion') - 1]->label() : '',
                    'address' => $employee->user->getMeta('address_address'),
                    'phone' => $employee->user->getMeta('phone_number'),
                    'email' => $employee->user->email_address,
                    'study' => '',
                    'contact' => '',
                    'mother' => '',
                ] : false;
            })->filter()->values()
        ];

        $json['Daftar karyawan baru'] = [
            'columns' => $json['Daftar karyawan']['columns'],
            'data' => $json['Daftar karyawan']['data']->filter(fn ($employee) => $employee['status'] == 'Baru')->values()
        ];

        $json['Daftar karyawan keluar'] = [
            'columns' => $json['Daftar karyawan']['columns'],
            'data' => $json['Daftar karyawan']['data']->filter(fn ($employee) => $employee['status'] == 'Keluar')->values()
        ];

        $json['Daftar kontrak karyawan'] = [
            'columns' => ['number' => 'No', 'name' => 'Nama', 'contract_category' => 'Kategori kontrak', 'contract_kd' => 'Nomor kontrak', 'validity_period' => 'Masa berlaku', 'contract_start_at' => 'Berlaku mulai', 'contract_end_at' => 'Berakhir pada', 'position' => 'Jabatan', 'contract_addendum' => 'No. SKD. Addendum Surat Kontrak', 'contract_addendum_desc' => 'Keterangan SKD. Addendum', 'permanent_kd' => 'No. SKD Karyawan Tetap', 'permanent_at' => 'Berlaku Mulai', 'permanent_addendum' => 'No. SKD. Addendum Karyawan Tetap', 'permanent_addendum_desc' => 'Keterangan SKD. Addendum Karyawan Tetap', 'nip' => 'No. Induk Karyawan', 'nik' => 'No. Induk Kependudukan', 'pob' => 'Tempat lahir', 'dob' => 'Tanggal lahir', 'address' => 'Alamat'],
            'data' => $employees->map(function ($employee, $index) use ($start_at, $end_at) {
                $contract = $employee->contracts->filter(
                    fn ($contract) => is_null($contract->end_at) || (($start_at->gte($contract->start_at) || $end_at->gte($contract->start_at)) && (($start_at->lte($contract->end_at) || $end_at->lte($contract->end_at))))
                )->first();
                return $contract ? [
                    'number' => $index + 1,
                    'name' => $employee->user->name,
                    'contract_category' => strtoupper($contract->contract->kd),
                    'contract_kd' => strtoupper($contract->kd),
                    'validity_period' => $contract->end_at ? $contract->end_at->diffInDays(now()) . ' hari' : '',
                    'contract_start_at' => $contract->start_at?->format('Y-m-d'),
                    'contract_end_at' => $contract->end_at?->format('Y-m-d'),
                    'position' => $contract->positions->last()?->position->name ?? '',
                    'contract_addendum' => '',
                    'contract_addendum_desc' => '',
                    'permanent_kd' => $employee->permanent_kd,
                    'permanent_at' => $employee->permanent_at?->format('Y-m-d'),
                    'permanent_addendum' => '',
                    'permanent_addendum_desc' => '',
                    'nip' => $employee->kd,
                    'nik' => $employee->user->getMeta('profile_nik'),
                    'pob' => $employee->user->getMeta('profile_pob'),
                    'dob' => $employee->user->getMeta('profile_dob'),
                    'address' => $employee->user->getMeta('address_address'),
                ] : false;
            })->filter()->values()
        ];

        $columns = ['number' => 'No', 'name' => 'Nama', 'contract_category' => 'Kategori kontrak', 'contract_kd' => 'Nomor kontrak', 'validity_period' => 'Masa berlaku', 'contract_start_at' => 'Berlaku mulai', 'contract_end_at' => 'Berakhir pada', 'position' => 'Jabatan', 'contract_addendum' => 'No. SKD. Addendum Surat Kontrak', 'contract_addendum_desc' => 'Keterangan SKD. Addendum', 'permanent_kd' => 'No. SKD Karyawan Tetap', 'permanent_at' => 'Berlaku Mulai', 'permanent_addendum' => 'No. SKD. Addendum Karyawan Tetap', 'permanent_addendum_desc' => 'Keterangan SKD. Addendum Karyawan Tetap', 'nip' => 'No. Induk Karyawan', 'nik' => 'No. Induk Kependudukan', 'pob' => 'Tempat lahir', 'dob' => 'Tanggal lahir', 'address' => 'Alamat'];
        $json['Riwayat kontrak karyawan'] = [
            'columns' => $columns,
            'data' => $employees->map(function ($employee, $index) use ($start_at, $end_at) {
                $contract = $employee->contracts->filter(
                    fn ($contract) => is_null($contract->end_at) || (($start_at->gte($contract->start_at) || $end_at->gte($contract->start_at)) && (($start_at->lte($contract->end_at) || $end_at->lte($contract->end_at))))
                )->first();
                return $contract ? [
                    'number' => $index + 1,
                    'name' => $employee->user->name,
                    'contract_category' => strtoupper($contract->contract->kd),
                    'contract_kd' => strtoupper($contract->kd),
                    'validity_period' => $contract->end_at ? $contract->end_at->diffInDays(now()) . ' hari' : '',
                    'contract_start_at' => $contract->start_at?->format('Y-m-d'),
                    'contract_end_at' => $contract->end_at?->format('Y-m-d'),
                    'position' => $contract->positions->last()?->position->name ?? '',
                    'contract_addendum' => '',
                    'contract_addendum_desc' => '',
                    'permanent_kd' => $employee->permanent_kd,
                    'permanent_at' => $employee->permanent_at?->format('Y-m-d'),
                    'permanent_addendum' => '',
                    'permanent_addendum_desc' => '',
                    'nip' => $employee->kd,
                    'nik' => $employee->user->getMeta('profile_nik'),
                    'pob' => $employee->user->getMeta('profile_pob'),
                    'dob' => $employee->user->getMeta('profile_dob'),
                    'address' => $employee->user->getMeta('address_address'),
                ] : false;
            })->filter()->values()
        ];

        $number = 1;
        $json['Riwayat kontrak karyawan'] = [
            'columns' => $columns,
            'data' => $employees->map(function ($employee) use (&$number) {
                return $employee->contracts->map(function ($contract, $index) use (&$number, $employee) {
                    return $contract ? [
                        'number' => $number++,
                        'name' => $employee->user->name,
                        'contract_category' => strtoupper($contract->contract->kd),
                        'contract_kd' => strtoupper($contract->kd),
                        'validity_period' => $contract->end_at ? $contract->end_at->diffInDays(now()) . ' hari' : '',
                        'contract_start_at' => $contract->start_at?->format('Y-m-d'),
                        'contract_end_at' => $contract->end_at?->format('Y-m-d'),
                        'position' => $contract->positions->last()?->position->name ?? '',
                        'contract_addendum' => '',
                        'contract_addendum_desc' => '',
                        'permanent_kd' => $employee->permanent_kd,
                        'permanent_at' => $employee->permanent_at?->format('Y-m-d'),
                        'permanent_addendum' => '',
                        'permanent_addendum_desc' => '',
                        'nip' => $employee->kd,
                        'nik' => $employee->user->getMeta('profile_nik'),
                        'pob' => $employee->user->getMeta('profile_pob'),
                        'dob' => $employee->user->getMeta('profile_dob'),
                        'address' => $employee->user->getMeta('address_address'),
                    ] : false;
                });
            })->flatten(1)
        ];

        $json['Rekapitulasi cuti karyawan'] = [
            'columns' => ['number' => 'No', 'name' => 'Nama', 'quota_yearly' => 'Kuota Cuti Tahunan', 'quota_holiday' => 'Kuota Libur Hari Raya', 'submission_yearly' => 'Pengajuan dan Pelaksanaan Cuti Tahunan', 'submission_yearly_cashable' => 'Kompensasi Cuti Tahunan', 'submission_holiday' => 'Pengajuan dan Pelaksanaan Libur Hari Raya', 'submission_holiday_freelance' => 'Pengajuan dan Pelaksanaan Libur Hari Raya sebagai Freelance', 'remain_yearly' => 'Sisa Cuti Tahunan', 'remain_holiday' => 'Sisa Libur Hari Raya',],
            'data' => $employees->map(function ($employee, $index) {
                $yearly_quotas = $employee->vacationQuotas->filter(fn ($quota) => $quota->category->type == VacationTypeEnum::YEARLY)->values();
                $holiday_quotas = $employee->vacationQuotas->filter(fn ($quota) => $quota->category->type == VacationTypeEnum::HOLIDAY)->values();
                return [
                    'number' => $index + 1,
                    'name' => $employee->user->name,
                    'quota_yearly' => ($quota_yearly = $yearly_quotas->sum('quota')),
                    'quota_holiday' => ($quota_holiday = $holiday_quotas->sum('quota')),
                    'submission_yearly' => ($submission_yearly = $yearly_quotas->map(fn ($quota) => $quota->vacations->filter(fn ($v) => empty($v->dates->first()['cashable']))->map(fn ($v) => $v->dates->count()))->flatten()->sum()),
                    'submission_yearly_cashable' => ($submission_yearly_cashable = $yearly_quotas->map(fn ($quota) => $quota->vacations->filter(fn ($v) => isset($v->dates->first()['cashable']))->map(fn ($v) => $v->dates->count()))->flatten()->sum()),
                    'submission_holiday' => ($submission_holiday = $holiday_quotas->map(fn ($quota) => $quota->vacations->map(fn ($v) => $v->dates->filter(fn ($d) => empty($d['f']))))->flatten()->count()),
                    'submission_holiday_freelance' => ($submission_holiday_freelance = $holiday_quotas->map(fn ($quota) => $quota->vacations->map(fn ($v) => $v->dates->filter(fn ($d) => isset($d['f']))))->flatten()->count()),
                    'remain_yearly' => ($remain_yearly = $quota_yearly - $submission_yearly - $submission_yearly_cashable) < 0 ? 0 : $remain_yearly,
                    'remain_holiday' => ($remain_holiday = $quota_holiday - $submission_holiday - $submission_holiday_freelance) < 0 ? 0 : $remain_holiday
                ];
            })
        ];

        $leave_ctgs = CompanyLeaveCategory::where('grade_id', userGrades())->all();
        $json['Rekapitulasi izin karyawan'] = [
            'columns' => [
                'number' => 'No',
                'name' => 'Nama',
                ...$leave_ctgs->mapWithKeys(fn ($ctg) => [
                    'leave' . $ctg->id => $ctg->name
                ])
            ],
            'data' => $employees->map(function ($employee, $index) use ($leave_ctgs, $start_at, $end_at) {
                return [
                    'number' => $index + 1,
                    'name' => $employee->user->name,
                    ...$leave_ctgs->mapWithKeys(fn ($ctg) => [
                        'leave' . $ctg->id => $employee->leaves->filter(fn ($leave) => $leave->category->is($ctg))?->map(fn ($l) => $l->dates->filter(fn ($date) => $start_at->lte($date['d']) && $end_at->gte($date['d']))->count())->sum() ?: 0
                    ])
                ];
            })
        ];

        $json['Karyawan kelipatan 5 tahun kerja'] = [
            'columns' => ['number' => 'No', 'name' => 'Nama', 'joined_at' => 'Tgl Masuk Kerja', 'worktime_year' => 'Masa Kerja;Tahun', 'worktime_month' => 'Masa Kerja;Bulan', 'worktime_day' => 'Masa Kerja;Hari'],
            'data' => $employees->filter(
                fn ($employee) => $employee->joined_at->diffInYears(now()->addYears(1)) % 5 == 0
            )->map(function ($employee, $index) {
                $diff = $employee->joined_at->diff(now());
                return [
                    'number' => $index + 1,
                    'name' => $employee->user->name,
                    'joined_at' => $employee->joined_at->format('Y-m-d'),
                    'worktime_year' => $diff->y . ' tahun',
                    'worktime_month' => $diff->m . ' bulan',
                    'worktime_day' => $diff->d . ' hari'
                ];
            })->values()
        ];

        $json['Karyawan baru tahun ' . $start_at->format('Y')] = [
            'columns' => $json['Daftar karyawan']['columns'],
            'data' => $json['Daftar karyawan']['data']->filter(fn ($employee) => substr($employee['joined_at'], 0, 4) == $start_at->format('Y'))->values()
        ];

        $json['Karyawan keluar tahun ' . $start_at->format('Y')] = [
            'columns' => $json['Daftar karyawan']['columns'],
            'data' => $json['Daftar karyawan']['data']->filter(fn ($employee) => isset($employee['exited_at']) && substr($employee['exited_at'], 0, 4) == $start_at->format('Y'))->values()
        ];

        return response()->json([
            'title' => ($title = 'Rekap data karyawan periode ' . $period),
            'subtitle' => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file' => Str::slug($title . '-' . time()),
            'sheets' => $json
        ]);
    }

    /**
     * Worktime
     */
    public function worktime(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $period = $start_at->isSameDay($end_at) ? $end_at->isoFormat('DD MMM YYYY') : $start_at->isoFormat('DD MMM') . ' s.d. ' . $end_at->isoFormat('DD MMM YYYY');

        $employees = Employee::where('grade_id', userGrades())->whereHas('contracts', fn ($contract) => $contract->whereActivePeriod($start_at, $end_at))
            ->with([
                'user.meta',
                'contracts' => fn ($c) => $c->whereActivePeriod($start_at, $end_at)->with('contract', 'positions.position.department')
            ])
            ->get();

        CompanyDepartment::where('grade_id', userGrades())->visible()->get()->each(function ($dept) use (&$sheets, $employees, $end_at) {
            $sheets[$dept->name] = [
                'columns' => [
                    'number' => 'No',
                    'name' => 'Nama',
                    'joined_at' => 'Tgl masuk kerja',
                    'worktime' => 'Masa kerja',
                    'round' => 'Pembulatan tahun',
                    'vacation' => 'Jatah cuti',
                    'position' => 'Jabatan',
                    'department' => 'Departemen'
                ],
                'data' => $employees->filter(fn ($e) => $e->contracts->first()?->position?->position->department->is($dept))->values()->map(fn ($employee, $index) => [
                    'number' => $index + 1,
                    'name' => $employee->user->name,
                    'joined_at' => $employee->joined_at->format('Y-m-d'),
                    'worktime' => $employee->joined_at->diffInYears($end_at) . ' tahun ' . $employee->joined_at->diffInMonths($end_at) % 12 . ' bulan',
                    'round' => ($tmk = $employee->joined_at->diffInYears($end_at)),
                    'vacation' => ($tmk = $end_at->format('Y') - $employee->joined_at->format('Y')) < 5 ? 12 : ($tmk % 5 == 1 ? 23 : 15),
                    'position' => $employee->contracts->first()?->position?->position->name,
                    'department' => $employee->contracts->first()?->position?->position->department->name
                ])->values()
            ];
        });

        return response()->json([
            'title' => ($title = 'Rekap masa kerja karyawan periode ' . $period),
            'subtitle' => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file' => Str::slug($title . '-' . time()),
            'sheets' => $sheets
        ]);
    }
}
