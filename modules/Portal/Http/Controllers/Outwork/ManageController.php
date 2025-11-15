<?php

namespace Modules\Portal\Http\Controllers\Outwork;

use Illuminate\Http\Request;
use Modules\HRMS\Models\EmployeeOutwork;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Outwork\Manage\UpdateRequest;
use Modules\Portal\Notifications\Outwork\Manage\ApprovedNotification;
use Modules\Portal\Notifications\Outwork\Manage\RejectedNotification;
use Modules\Portal\Notifications\Outwork\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Outwork\Manage\ManageWaNotification;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $employee = $user->employee->load('position.position.children');

        $outworks = EmployeeOutwork::with('approvables.userable.position', 'employee.user', 'category')
            ->whereHas('approvables', fn($approvable) => $approvable->where('userable_id', $employee->position->id))
            ->where('grade_id', userGrades())
            ->whenOnlyPending($request->get('pending'))
            ->search($request->get('search'))
            ->latest()
            ->paginate($request->get('limit', 10));

        $pending_outworks_count = EmployeeOutwork::whereHas('employee.position', fn($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
            ->whereHas('employee', function($query){
                $query->where('grade_id', userGrades());
            })->whenOnlyPending(true)
            ->count();

        return view('portal::outwork.manage.index', compact('user', 'employee', 'outworks', 'pending_outworks_count'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeOutwork $outwork, Request $request)
    {
        $user = $request->user();
        $employee = $user->employee;

        $results = ApprovableResultEnum::cases();

        $outwork = $outwork->load('approvables.userable.position', 'category');

        return view('portal::outwork.manage.show', compact('user', 'employee', 'outwork', 'results'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyApprovable $approvable, UpdateRequest $request)
    {
        $approvable->update($request->transformed()->toArray());
        $approved = $request->input('result') == ApprovableResultEnum::APPROVE->value;

        $approvable->modelable->update(['paidable_at' => $approved && $approvable->is($approvable->modelable->approvables->sortByDesc('level')->first()) ? now() : null]);

        // Handle notifications
        if ($approved) {
            //$approvable->modelable->employee->user->notify(new ApprovedNotification($approvable->modelable, $approvable));
            // $approvable->modelable->employee->user->notify(new ManageWaNotification(
            //     'Pengajuan insentif anda sudah disetujui ' .
            //         ', silakan cek pada link berikut: ' . route('portal::outwork.submission.show', ['outwork' => $approvable->id]),
            //     $approvable->modelable->employee->user
            // ));

            if ($superior = $approvable->modelable->approvables->sortBy('level')->filter(fn($a) => $a->level > $approvable->level)->first()) {
                //  $superior->userable->employee->user->notify(new SubmissionNotification($approvable->modelable, $approvable->userable));

                // $superior->userable->employee->user->notify(new ManageWaNotification(
                //     'Pengajuan insentif atas nama ' . $approvable->modelable->employee->user->name . ' sudah disetujui oleh ' . $superior->userable->employee->user->name .
                //         ', silakan cek pada link berikut: ' . route('portal::outwork.submission.show', ['outwork' => $approvable->id]),
                //     $superior->userable->employee->user
                // ));
            }
        }

        if ($request->input('result') == ApprovableResultEnum::REJECT->value) {
            //$approvable->modelable->employee->user->notify(new RejectedNotification($approvable->modelable, $approvable));
            $approvable->modelable->employee->user->notify(new ManageWaNotification(
                'Pengajuan insentif anda ditolak' .
                    ', silakan cek pada link berikut: ' . route('portal::outwork.submission.show', ['outwork' => $approvable->id]),
                $approvable->modelable->employee->user
            ));
        }

        return redirect()->next()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
    }
}
