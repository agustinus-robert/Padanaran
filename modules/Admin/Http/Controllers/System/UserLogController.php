<?php

namespace Modules\Admin\Http\Controllers\System;

use Illuminate\Http\Request;
use Modules\Account\Models\User;
use Modules\Account\Models\UserLog;
use Modules\Admin\Http\Controllers\Controller;

class UserLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', UserLog::class);

        $user = User::find($request->get('user'));
        $logs = UserLog::with('user')
            ->latest()
            ->search($request->get('search'))
            ->whenUserId($request->get('user'))
            ->paginate($request->get('limit', 10));

        return view('admin::system.user-logs.index', compact('logs', 'user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserLog $log)
    {
        $this->authorize('destroy', $log);
        if ($log->delete()) {
            return redirect()->next()->with('success', 'Log <strong>#' . $log->id . '</strong> berhasil dihapus');
        }
        return redirect()->fail();
    }
}
