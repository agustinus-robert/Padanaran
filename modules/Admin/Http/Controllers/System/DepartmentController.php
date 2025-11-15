<?php

namespace Modules\Admin\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Models\Departement;
use Modules\Admin\Http\Requests\System\Departement\StoreRequest;
use Modules\Admin\Http\Requests\System\Departement\UpdateRequest;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Repositories\CompanyDepartmentRepository;

class DepartmentController extends Controller
{
    use CompanyDepartmentRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Departement::class);

        $departments = Departement::withCount('positions')
            ->whenTrashed($request->get('trash'))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        $departments_count = Departement::count();

        return view('admin::system.departements.index', compact('departments', 'departments_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', Departement::class);

        $departments = Departement::all();

        return view('admin::system.departements.create', compact('departments'));
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
    public function show(Departement $department)
    {
        $this->authorize('update', $department);

        $departments = Departement::all();

        return view('admin::system.departements.show', compact('departments', 'department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Departement $department, UpdateRequest $request)
    {
        if ($department = $this->updateCompanyDepartment($department, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Departemen dengan nama <strong>' . $department->name . ' (' . $department->kd . ')</strong> telah berhasil diperbarui.');
        }

        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departement $department)
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
    public function restore(Departement $department)
    {
        $this->authorize('restore', $department);

        if ($department = $this->restoreCompanyDepartment($department)) {

            return redirect()->next()->with('success', 'Departemen dengan nama <strong>' . $department->name . ' (' . $department->kd . ')</strong> telah berhasil dipulihkan.');
        }

        return redirect()->fail();
    }
}
