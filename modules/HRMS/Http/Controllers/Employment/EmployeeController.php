<?php

namespace Modules\HRMS\Http\Controllers\Employment;

use Illuminate\Http\Request;
use Modules\Academic\Models\Academic;
use Modules\Account\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Academic\Models\EmployeeTeacher;
use Modules\Core\Models\CompanyContract;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Repositories\EmployeeRepository;
use Modules\HRMS\Http\Requests\Employment\Employee\StoreRequest;
use Modules\HRMS\Http\Requests\Employment\Employee\UpdateRequest;
use Modules\HRMS\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    use EmployeeRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Employee::class);

        $employees = Employee::with('user.meta', 'contract.positions.position')
            ->where('grade_id', userGrades())
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));

        $employees_count = Employee::where('grade_id', userGrades())->count();

        return view('hrms::employment.employees.index', compact('employees', 'employees_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', Employee::class);

        $acdmcs = Academic::orderByDesc('id')->get();
        $contracts = CompanyContract::all();
        return view('hrms::employment.employees.create', compact('contracts', 'acdmcs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            if ($employee = $this->storeEmployee($request->transformed()->toArray())) {
                // return redirect()->next()->with('success', 'Karyawan baru dengan nama <strong>' . $employee->user->name . ' (' . $employee->user->username . ')</strong> berhasil dibuat dengan sandi <strong>' . $request->password . '</strong>');
               // $password = User::generatePassword();
                // $user = User::find($request->input('user_id'));
                $data = [
                    'nuptk' => $request->nuptk,
                    'employee_id' => $employee->id
                ];

                $teacher = EmployeeTeacher::insertFromUser($data);
                //if ($request->get('user') == 1) {
                // } else {
                //     $teacher = EmployeeTeacher::completeInsert($request->all(), $password);
                // }

                DB::commit();
                return redirect()->next()->with('success', 'Data berhasil ditambahkan.');
            }
            // return redirect()->fail();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());


            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data karyawan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Employee $employee, UpdateRequest $request)
    {
        DB::beginTransaction();

        try {
            $this->updateEmployee($employee, $request->transformed()->toArray());
            // if ($employee = $this->updateEmployee($employee, $request->transformed()->toArray())) {
            //     $teacher = EmployeeTeacher::completeUpdate($employee, $request->transformed()->toArray());
            // }

            DB::commit();
            return redirect()->next()->with('success', 'Data berhasil dirubah.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        $employee = $employee->load('user.meta', 'contracts', 'positions');

        return view('hrms::employment.employees.show', compact('employee'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $this->authorize('destroy', $employee);
        if ($employee = $this->destroyEmployee($employee)) {
            return redirect()->next()->with('success', 'Karyawan <strong>' . $employee->user->name . ' (' . $employee->user->username . ')</strong> berhasil dihapus');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Employee $employee)
    {
        $this->authorize('restore', $employee);
        if ($employee = $this->restoreEmployee($employee)) {
            return redirect()->next()->with('success', 'Karyawan <strong>' . $employee->user->name . ' (' . $employee->user->username . ')</strong> berhasil dipulihkan');
        }
        return redirect()->fail();
    }
}
