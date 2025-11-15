<?php

namespace Modules\Portal\Http\Controllers\Outwork;

use Illuminate\Http\Request;
use Modules\Core\Enums\PositionLevelEnum;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyOutworkCategory;
use Modules\HRMS\Models\EmployeeOutwork;
use Modules\HRMS\Models\EmployeePosition;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Outwork\Submission\StoreRequest;
use Modules\Portal\Notifications\Outwork\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Outwork\Cancelation\CanceledNotification;
use Modules\Account\Notifications\AccountNotification;
use Modules\Portal\Notifications\Outwork\Submission\SubmissionWaNotification;

class SubmissionController extends Controller
{
    private $superiors = [];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employee = $request->user()->employee;

        $outworks = $employee->outworks()
            ->withTrashed()
            ->with('approvables.userable.position', 'category')
            ->search($request->get('search'))
            ->whenPeriod($request->get('start_at'), $request->get('end_at'))
            ->latest()
            ->paginate($request->get('limit', 10));

        $approvers = EmployeePosition::active()->whereHas('position', fn($position) => $position->whereIn('level', array_column(config('modules.core.features.services.outworks.approvable_steps'), 'value')))->get();

        return view('portal::outwork.submission.index', compact('employee', 'outworks', 'approvers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employee = $request->user()->employee;

        $steps = array_filter(
            config('modules.core.features.services.outworks.approvable_steps', []),
            fn($step) => empty($step['hide_from_input'])
        );

        foreach ($steps as $step => $model) {
            switch ($model['type']) {
                case 'parent_position_level':
                    array_push($this->superiors, [
                        'step' => $step,
                        'label' => PositionLevelEnum::tryFrom($model['value'])->label(),
                        'positions' => $employee->position->position->load(['parents.employeePositions' => fn($position) => $position->active()->with('position', 'employee.user')])->parents->where('level.value', $model['value'])
                    ]);
                    break;
                default:
                    break;
            }
        }


        $superiors = array_filter($this->superiors, fn($superior) => count($superior['positions']));
        $categories = CompanyOutworkCategory::where('grade_id', userGrades())->get()->groupBy('name');

        return view('portal::outwork.submission.create', compact('employee', 'superiors', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $employee = $request->user()->employee;

        $outwork = $employee->outworks()->create(
            array_merge($request->transform(),
            ['grade_id' => userGrades()]
        ));

        // Assign approvable based approvable_steps configuration
        foreach (config('modules.core.features.services.outworks.approvable_steps', []) as $index => $model) {
            if ($model['type'] == 'parent_position_level') {
                $positions = $employee->position->position->parents->where('level.value', $model['value'])->each(
                    fn($position) =>
                    $outwork->createApprovable(empty($model['hide_from_input']) ? $position->employeePositions()->active()->find($request->input('approvables.' . $index)) : $position->employeePositions()->active()->first())
                );
            }
        }

        // Handle notifications
        if ($approvable = $outwork->approvables()->orderBy('level')->first()) {
            $approvable->userable->getUser()->notify(new SubmissionNotification($outwork, null));
            // $approvable->userable->getUser()->notify(new SubmissionWaNotification(
            //     'Anda mendapatkan notifikasi pengajuan insentif dari ' . $employee->user->name .
            //         ', silakan cek pada link berikut: ' . route('portal::outwork.submission.show', ['outwork' => $outwork->id]),
            //     $approvable->userable->getUser()
            // ));
        } else {
            $outwork->update(['paidable_at' => now()]);
        }

        return redirect()->route('portal::outwork.submission.index')->with('success', isset($approvable) ? 'Pengajuan kegiatan sudah terkirim, silakan tunggu notifikasi selanjutnya dari atasan!' : 'Pengajuan sudah tersimpan dan sudah disetujui otomatis oleh sistem, terima kasih!');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeOutwork $outwork, Request $request)
    {
        $user = $request->user();
        $employee = $user->employee;

        $outwork = $outwork->load('approvables.userable.position', 'category');

        return view('portal::outwork.submission.show', compact('user', 'employee', 'outwork'));
    }

    public function destroy(EmployeeOutwork $outwork)
    {
        $outwork->delete();

        // Handle notifications
        if ($approvable = $outwork->approvables()->whereNotIn('result', [ApprovableResultEnum::PENDING])->orderBy('level')->first()) {
            $approvable->userable->employee->user->notify(new CanceledNotification($outwork));
        }

        return redirect()->route('portal::outwork.submission.index')->with('success', 'Pengajuan telah dibatalkan dan kami telah mengirim notifikasi ke atasan!');
    }
}
