<?php

namespace Modules\Portal\Http\Controllers\Overtime;

use Illuminate\Http\Request;
use Modules\Account\Notifications\AccountNotification;
use Modules\HRMS\Models\EmployeeOvertime;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Overtime\Manage\UpdateRequest;
use Modules\Portal\Notifications\Overtime\Manage\ApprovedNotification;
use Modules\Portal\Notifications\Overtime\Manage\RejectedNotification;
use Modules\Portal\Notifications\Overtime\Submission\SubmissionNotification;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $employee = $user->employee->load('position.position.children');
        $start_at = $request->get('start_at', date('Y-m-01', strtotime("-1 months"))) . ' 00:00:00';
        $end_at = $request->get('end_at', date('Y-m-t')) . ' 23:59:59';

        $overtimes = EmployeeOvertime::with('approvables.userable.position', 'employee.user')
            ->whereHas('approvables', fn($approvable) => $approvable->where('userable_id', $employee->position->id))
            ->orWhere('scheduled_by', $employee->id)
            ->whenOnlyPending($request->get('pending'))
            ->whenPeriod($start_at, $end_at)
            ->search($request->get('search'))
            ->latest()
            ->paginate($request->get('limit', 10));

        $pending_overtimes_count = EmployeeOvertime::whereHas('employee.position', fn($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
            ->whenOnlyPending(true)
            ->count();

        return view('portal::overtime.manage.index', compact('user', 'employee', 'overtimes', 'pending_overtimes_count', 'start_at', 'end_at'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeOvertime $overtime, Request $request)
    {
        $user = $request->user();
        $employee = $user->employee;

        $results = ApprovableResultEnum::cases();

        $overtime = $overtime->load('approvables.userable.position');

        return view('portal::overtime.manage.show', compact('user', 'employee', 'overtime', 'results'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyApprovable $approvable, UpdateRequest $request)
    {
        $approvable->update($request->transformed()->toArray());
        $approved = $request->input('result') == ApprovableResultEnum::APPROVE->value;

        // Handle notifications
        if ($approved) {
            $approvable->modelable->employee->user->notify(new ApprovedNotification($approvable->modelable, $approvable));
            // $approvable->modelable->employee->user->notify(new AccountNotification($approvable->modelable->employee->user->name . ' mengajukan lembur ' . $approvable->modelable->name . ', silakan cek pada link berikut ' . route('portal::overtime.submission.show', ['overtime' => $approvable->modelable->id]), $approvable->modelable->employee->user));
            if ($superior = $approvable->modelable->approvables->sortBy('level')->filter(fn($a) => $a->level > $approvable->level)->first()) {
                $superior->userable->employee->user->notify(new SubmissionNotification($approvable->modelable, $approvable->userable));
                $superior->userable->employee->user->notify(new AccountNotification($approvable->modelable->employee->user->name . ' mengajukan lembur ' . $approvable->modelable->name . ', silakan cek pada link berikut ' . route('portal::overtime.manage.show', ['overtime' => $approvable->modelable->id]), $superior->userable->employee->user));
            }
        }

        if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
            $approvable->modelable->employee->user->notify(new RejectedNotification($approvable->modelable, $approvable));
            $approvable->modelable->employee->user->notify(new AccountNotification('Maaf, pengajuan lembur ' . $approvable->modelable->name . ' ditolak, silakan cek pada link berikut ' . route('portal::overtime.submission.show', ['overtime' => $approvable->modelable->id]), $approvable->modelable->employee->user));
        }

        $approvable->modelable->update(['paidable_at' => $approved && $approvable->is($approvable->modelable->approvables->sortByDesc('level')->first()) ? now() : null]);

        return redirect()->next()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
    }

    public function destroy(EmployeeOvertime $overtime)
    {
        $this->authorize('destroy', $overtime);

        $tmp = $overtime;
        $overtime->delete();

        return redirect()->back()->with('success', 'Pengajuan overtime <strong>' . $tmp->employee->user->name . '</strong> berhasil dihapus');
    }
}
