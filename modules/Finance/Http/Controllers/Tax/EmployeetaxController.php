<?php

namespace Modules\Finance\Http\Controllers\Tax;

use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Account\Models\User;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\Finance\Http\Requests\Tax\Employee\StoreRequest;
use Modules\Finance\Http\Requests\Tax\Employee\UpdateRequest;

class EmployeetaxController extends Controller
{
    /**
     * Define the primary meta keys for resource
     */
    private $metaKeys = [
        'tax_number',
        'tax_address',
        'tax_file'
    ];

    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        return view('finance::tax.employeetaxs.index', [
            'start_at'    => Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00'),
            'end_at'      => Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59'),
            'departments' => CompanyDepartment::where('grade_id', userGrades())->visible()->with('positions')->get(),
            'employees'   => Employee::isActive()->with('user.meta', 'position.position')->where('grade_id', userGrades())->whenTrashed($request->get('trashed'))->paginate($request->get('limit', 10)),
        ]);
    }

    public function create()
    {
        return view('finance::tax.employeetaxs.create', [
            'employees' => Employee::with('user', 'position.position.department')->get()->groupBy('position.position.department.name')
        ]);
    }

    public function store(StoreRequest $request)
    {
        $user = User::find($request->transformed()->toArray()['user_id']);
        if ($user) {
            $user->setManyMeta(Arr::only($request->transformed()->toArray(), $this->metaKeys));
            $request->user()->log('memperbarui informasi NPWP pengguna ' . $user->name . ' <strong>[ID: ' . $user->id . ']</strong>', User::class, $user->id);
            return redirect()->next()->with('success', 'NPWP karyawan <strong>' . $user->name . '</strong> berhasil disimpan.');
        }
        return redirect()->fail();
    }

    public function show(Employee $employeetax)
    {
        return view('finance::tax.employeetaxs.show', [
            'employee' => $employeetax
        ]);
    }

    public function update(Employee $employeetax, UpdateRequest $request)
    {
        $user = $employeetax->user;
        if ($user) {
            $user->setManyMeta(Arr::only($request->transformed()->toArray(), $this->metaKeys));
            $request->user()->log('memperbarui informasi NPWP pengguna ' . $user->name . ' <strong>[ID: ' . $user->id . ']</strong>', User::class, $user->id);
            return redirect()->next()->with('success', 'NPWP karyawan <strong>' . $user->name . '</strong> berhasil disimpan.');
        }
        return redirect()->fail();
    }
}
