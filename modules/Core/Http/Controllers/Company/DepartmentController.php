<?php

namespace Modules\Core\Http\Controllers\Company;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Http\Requests\Company\Department\StoreRequest;
use Modules\Core\Http\Requests\Company\Department\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyDepartmentRepository;

class DepartmentController extends Controller
{
    use CompanyDepartmentRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyDepartment::class);

        $departments = CompanyDepartment::withCount('positions')
            ->where('grade_id', userGrades())
            ->whenTrashed($request->get('trash'))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        $departments_count = CompanyDepartment::count();

        return view('core::company.departments.index', compact('departments', 'departments_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', CompanyDepartment::class);

        $departments = CompanyDepartment::where('grade_id', userGrades())->get();

        return view('core::company.departments.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($department = $this->storeCompanyDepartment($request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Departemen dengan nama <strong>' . $department->name . ' (' . $department->kd . ')</strong> telah berhasil dibuat.');
        }

        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyDepartment $department)
    {
        $this->authorize('update', $department);

        $departments = CompanyDepartment::where('grade_id', userGrades())->get();

        return view('core::company.departments.show', compact('departments', 'department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyDepartment $department, UpdateRequest $request)
    {
        if ($department = $this->updateCompanyDepartment($department, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Departemen dengan nama <strong>' . $department->name . ' (' . $department->kd . ')</strong> telah berhasil diperbarui.');
        }

        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyDepartment $department)
    {
        $this->authorize('destroy', $department);

        if ($department = $this->destroyCompanyDepartment($department)) {

            return redirect()->next()->with('success', 'Departemen dengan nama <strong>' . $department->name . ' (' . $department->kd . ')</strong> telah berhasil dihapus.');
        }

        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanyDepartment $department)
    {
        $this->authorize('restore', $department);

        if ($department = $this->restoreCompanyDepartment($department)) {

            return redirect()->next()->with('success', 'Departemen dengan nama <strong>' . $department->name . ' (' . $department->kd . ')</strong> telah berhasil dipulihkan.');
        }

        return redirect()->fail();
    }
}
