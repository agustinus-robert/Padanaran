<?php

namespace Modules\Core\Http\Controllers\System;

use Hash;
use Illuminate\Http\Request;
use Modules\Account\Models\User;
use Modules\Account\Repositories\UserRepository;
use Modules\Core\Http\Requests\System\User\StoreRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\CompanyRole;

class UserController extends Controller
{
    use UserRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', User::class);

        $users = User::with('roles', 'meta', 'teacher')
            ->whereHas('teacher', function($query){
                $query->where('grade_id', userGrades());
            })
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));

        $users_count = User::with('teacher')->whereHas('teacher', function($query){
            $query->where('grade_id', userGrades());
        })->orWhereHas('student', function($query) {
            $query->where('grade_id', userGrades());
        })->count();

        return view('core::system.users.index', compact('users', 'users_count'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($user = $this->storeUser($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Pengguna <strong>' . $user->name . ' (' . $user->username . ')</strong> berhasil dibuat dengan password <strong>' . $request->password . '</strong>');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $roles = CompanyRole::where('grade_id', userGrades())->get();

        return in_array($request->get('page', 'profile'), ['profile', 'username', 'email', 'phone', 'role'])
            ? view('core::system.users.show', compact('user', 'roles'))
            : abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        if ($user = $this->removeUser($user)) {
            return redirect()->next()->with('success', 'Pengguna <strong>' . $user->name . ' (' . $user->username . ')</strong> berhasil dihapus');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(User $user)
    {
        $this->authorize('restore', $user);
        if ($user = $this->restoreUser($user)) {
            return redirect()->next()->with('success', 'Pengguna <strong>' . $user->name . ' (' . $user->username . ')</strong> berhasil dipulihkan');
        }
        return redirect()->fail();
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(User $user)
    {
        $this->authorize('restore', $user);
        if ($user = $this->killUser($user)) {
            return redirect()->next()->with('success', 'Pengguna <strong>' . $user->name . ' (' . $user->username . ')</strong> berhasil dihapus permanen dari sistem');
        }
        return redirect()->fail();
    }

    /**
     * Reset password from storage.
     */
    public function repass(User $user)
    {
        $this->authorize('update', $user);
        if ($password = $this->resetPasswordUser($user)) {
            return redirect()->next()->with('success', 'Sandi pengguna <strong>' . $user->name . ' (' . $user->username . ')</strong> berhasil diperbarui menjadi <strong>' . $password . '</strong>');
        }
        return redirect()->fail();
    }

    /**
     * Cross login with bypassing user password.
     */
    public function login(Request $request, User $user)
    {
        $this->authorize('cross-login', $user);

        if (!Hash::check($request->input('password'), $request->user()->password))
            return redirect()->fail('Mohon maaf, sandi yang Anda masukkan salah, silakan ulangi kembali!');

        if ($user = $this->crossLoginUser($request, $user)) {
            return redirect()->route('account::home')->with('success', 'Anda telah masuk ke pengguna <strong>' . $user->name . ' (' . $user->username . ')</strong>, data Anda tetap terekam oleh sistem.');
        }

        return redirect()->fail();
    }
}
