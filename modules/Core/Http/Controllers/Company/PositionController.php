<?php

namespace Modules\Core\Http\Controllers\Company;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyRole;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyPosition;
use Modules\Core\Http\Requests\Company\Position\StoreRequest;
use Modules\Core\Http\Requests\Company\Position\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyPositionRepository;

class PositionController extends Controller
{
    use CompanyPositionRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyPosition::class);

        $departments = CompanyDepartment::all();

        $positions = CompanyPosition::with('department')
            ->whereHas('department', function($query){
                $query->where('grade_id', userGrades());
            })
            ->withCount(['employeePositions' => fn($position) => $position->active()])
            ->whenDepartmentId($request->get('department'))
            ->whenTrashed($request->get('trash'))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        $positions_count = CompanyPosition::count();

        return view('core::company.positions.index', compact('departments', 'positions', 'positions_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', CompanyPosition::class);

        $roles = CompanyRole::where('grade_id', userGrades())->get();
        $departments = CompanyDepartment::where('grade_id', userGrades())->get();
        $positions = CompanyPosition::with('department')
        ->whereHas('department', function($query){
            $query->where('grade_id', userGrades());
        })
        ->get()->groupBy('department.name');

        return view('core::company.positions.create', compact('roles', 'positions', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($department = $this->storeCompanyPosition($request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Jabatan baru dengan nama <strong>' . $department->name . ' (' . $department->kd . ')</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyPosition $position)
    {
        $this->authorize('update', $position);

        $position = $position->load('children', 'parents', 'meta');

        $roles = CompanyRole::where('grade_id', userGrades())->get();
        $departments = CompanyDepartment::where('grade_id', userGrades())->get();
        $positions = CompanyPosition::with('department')
        ->whereHas('department', function($query){
            $query->where('grade_id', userGrades());
        })->get()->groupBy('department.name');

        return view('core::company.positions.show', compact('position', 'roles', 'departments', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyPosition $position, UpdateRequest $request)
    {
        if ($position = $this->updateCompanyPosition($position, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Jabatan <strong>' . $position->name . ' (' . $position->kd . ')</strong> telah berhasil diperbarui.');
        }

        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyPosition $position)
    {
        $this->authorize('destroy', $position);

        if ($position = $this->destroyCompanyPosition($position)) {

            return redirect()->next()->with('success', 'Jabatan <strong>' . $position->name . ' (' . $position->kd . ')</strong> telah berhasil dihapus.');
        }

        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanyPosition $position)
    {
        $this->authorize('restore', $position);

        if ($position = $this->restoreCompanyPosition($position)) {

            return redirect()->next()->with('success', 'Jabatan <strong>' . $position->name . ' (' . $position->kd . ')</strong> telah berhasil dipulihkan.');
        }

        return redirect()->fail();
    }
}
