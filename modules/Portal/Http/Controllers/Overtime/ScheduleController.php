<?php

namespace Modules\Portal\Http\Controllers\Overtime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Account\Notifications\AccountNotification;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeOvertime;
use Modules\HRMS\Repositories\EmployeeScheduleRepository;
use Modules\HRMS\Repositories\EmployeeRepository;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Overtime\Manage\Schedule\StoreRequest;

class ScheduleController extends Controller
{
    use EmployeeRepository, EmployeeScheduleRepository;

    /**
     * Display a listing of the resource.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $employee = $user->employee->load('position.position.children');

        $arr = $employee->position->position->children->count()
            ?   array_merge(array($employee->position->position->id), $employee->position->position->children->pluck('id')->toArray())
            :   array($user->employee->id);

        $employees = $employee->position->position->children->count()
            ? Employee::with('user', 'position')->whereHas('position', fn($position) => $position->whereIn('position_id', $arr))->get()->sortBy(fn($item) => $item->position->position->level)->groupBy('position.position.name')
            : Employee::with('user', 'position')->whereId($arr)->get()->groupBy('position.position.name');

        return view('portal::overtime.manage.schedule.create', compact('employees', 'user'));
    }

    /**
     * Store a resource to database.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $overtime = new EmployeeOvertime($request->transformed()->toArray());
            if ($overtime->save()) {
                $request->user()->log('menambahkan perintah lembur ' . $overtime->employee->user->name . ' <strong>[ID: ' . $overtime->employee->user->id . ']</strong>', EmployeeOvertime::class, $overtime->id);
                DB::commit();
                $overtime->employee->user->notify(new AccountNotification('Anda telah mendapatkan perintah lembur ' . $overtime->name . ', silakan cek pada link berikut ' . route('portal::overtime.submission.show', ['overtime' => $overtime->id]), $overtime->employee->user));
                return redirect()->next()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
            }
            Log::error('Terjadi kesalahan saat menyimpan data', ['error' => $overtime]);
            return redirect()->fail();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Terjadi kesalahan', ['error' => $th->getMessage()]);
            return redirect()->fail();
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(Request $request)
    {
        $employee = $request->user()->employee;

        return view('portal::schedules.index', compact('month', 'workshifts', 'employee', 'moments', 'schedules', 'vacations', 'scheduleCount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $employee = $request->user()->employee;

        return redirect()->next()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
    }
}
