<?php

namespace Modules\Core\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Models\Permission;
use Modules\Core\Models\CompanyRole;
use Modules\Core\Http\Requests\Company\Role\StoreRequest;
use Modules\Core\Http\Requests\Company\Role\UpdateRequest;
use Modules\Core\Http\Requests\Company\Role\SyncPermissionsRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyRoleRepository;

class RoleController extends Controller
{
    use CompanyRoleRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyRole::class);

        $roles = CompanyRole::with('permissions')
            ->where('grade_id', userGrades())
            ->withCount('users')
            ->whenTrashed($request->get('trash'))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        $roles_count = CompanyRole::count();

        return view('core::company.roles.index', compact('roles', 'roles_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', CompanyRole::class);

        $roles = CompanyRole::withoutTrashed()->get();

        return view('core::company.roles.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($role = $this->storeCompanyRole($request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Peran dengan nama <strong>' . $role->name . ' (' . $role->kd . ')</strong> telah berhasil dibuat.');
        }

        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyRole $role)
    {
        $this->authorize('update', $role);

        $role->loadCount('users');
        $permissions = Permission::all();

        return view('core::company.roles.show', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRole $role, UpdateRequest $request)
    {
        if ($role = $this->updateCompanyRole($role, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Peran <strong>' . $role->name . ' (' . $role->kd . ')</strong> telah berhasil diperbarui.');
        }

        return redirect()->fail();
    }

    /**
     * Sync permissions with the specified resource in storage.
     */
    public function permissions(CompanyRole $role, SyncPermissionsRequest $request)
    {
        if ($role = $this->syncCompanyRolePermissions($role, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Hak akses peran <strong>' . $role->name . ' (' . $role->kd . ')</strong> telah berhasil diperbarui.');
        }

        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyRole $role)
    {
        $this->authorize('destroy', $role);

        if ($role = $this->destroyCompanyRole($role)) {

            return redirect()->next()->with('success', 'Peran <strong>' . $role->name . ' (' . $role->kd . ')</strong> telah berhasil dihapus.');
        }

        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanyRole $role)
    {
        $this->authorize('restore', $role);

        if ($role = $this->restoreCompanyRole($role)) {

            return redirect()->next()->with('success', 'Peran <strong>' . $role->name . ' (' . $role->kd . ')</strong> telah berhasil dipulihkan.');
        }

        return redirect()->fail();
    }
}
