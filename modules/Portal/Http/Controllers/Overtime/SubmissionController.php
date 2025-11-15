<?php

namespace Modules\Portal\Http\Controllers\Overtime;

use Illuminate\Http\Request;
use Modules\Account\Notifications\AccountNotification;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Enums\PositionLevelEnum;
use Modules\HRMS\Models\EmployeeOvertime;
use Modules\HRMS\Models\EmployeePosition;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Overtime\Submission\StoreRequest;
use Modules\Portal\Http\Requests\Overtime\Submission\UpdateRequest;
use Modules\Portal\Notifications\Overtime\Submission\SubmissionNotification;
use Modules\Portal\Notifications\Overtime\Submission\CanceledNotification;

class SubmissionController extends Controller
{
    private $superiors = [];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employee = $request->user()->employee;
        $start_at = $request->get('start_at', cmp_cutoff(0)->format('Y-m-d'));
        $end_at   = $request->get('end_at', cmp_cutoff(1)->format('Y-m-d'));

        $overtimes = $employee->overtimes()
            ->withTrashed()
            ->with('approvables.userable.position')
            ->search($request->get('search'))
            ->whenPeriod($start_at, $end_at)
            ->latest()
            ->paginate($request->get('limit', 10));

        $approvers = EmployeePosition::active()->whereHas('position', fn($position) => $position->whereIn('level', array_column(config('modules.core.features.services.overtimes.approvable_steps'), 'value')))->get();

        return view('portal::overtime.submission.index', compact('employee', 'overtimes', 'approvers', 'start_at', 'end_at'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employee = $request->user()->employee;

        $steps = array_filter(
            config('modules.core.features.services.overtimes.approvable_steps', []),
            fn($step) => empty($step['hide_from_input'])
        );

        foreach ($steps as $step => $model) {
            switch ($model['type']) {
                case 'parent_position_level':
                    array_push($this->superiors, [
                        'step' => $step,
                        'required' =>  $model['required'],
                        'label' => PositionLevelEnum::tryFrom($model['value'])->label(),
                        'positions' => $employee->position->position->load(['parents.employeePositions' => fn($position) => $position->active()->with('position', 'employee.user')])->parents->where('level.value', $model['value'])
                    ]);
                    break;
                default:
                    break;
            }
        }

        $superiors = array_filter($this->superiors, fn($superior) => count($superior['positions']));

        return view('portal::overtime.submission.create', compact('employee', 'superiors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $employee = $request->user()->employee;

        $overtime = $employee->overtimes()->create($request->transform());

        // Assign approvable based approvable_steps configuration
        foreach (config('modules.core.features.services.overtimes.approvable_steps', []) as $index => $model) {
            if ($model['type'] == 'parent_position_level') {
                $employee->position->position->parents->where('level.value', $model['value'])->each(
                    fn($position) =>
                    $overtime->createApprovable(empty($model['hide_from_input']) ? $position->employeePositions()->active()->find($request->input('approvables.' . $index)) : $position->employeePositions()->active()->first())
                );
            }
        }

        // Handle notifications
        if ($approvable = $overtime->approvables()->orderBy('level')->first()) {
            $approvable->userable->getUser()->notify(new SubmissionNotification($overtime, null));
            // $approvable->userable->getUser()->notify(new AccountNotification($overtime->employee->user->name . ' mengajukan lembur ' . $overtime->name . ', silakan cek pada link berikut ' . route('portal::overtime.manage.show', ['overtime' => $overtime->id]), $approvable->userable->getUser()));
        } else {
            $overtime->update(['paidable_at' => now()]);
        }

        return redirect()->route('portal::overtime.submission.index')->with('success', isset($approvable) ? 'Pengajuan lembur sudah terkirim, silakan tunggu notifikasi selanjutnya dari atasan!' : 'Pengajuan sudah tersimpan dan sudah disetujui otomatis oleh sistem, terima kasih!');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeOvertime $overtime, Request $request)
    {
        $user = $request->user();
        $employee = $user->employee;

        $overtime = $overtime->load('approvables.userable.position');

        return view('portal::overtime.submission.show', compact('user', 'employee', 'overtime'));
    }

    /**
     * Update a resource in storage.
     */
    public function update(EmployeeOvertime $overtime, UpdateRequest $request)
    {
        $employee = $request->user()->employee;
        $overtime->fill($request->transformed()->toArray());
        if ($overtime->save()) {
            // Assign approvable based approvable_steps configuration
            foreach (config('modules.core.features.services.overtimes.approvable_steps', []) as $index => $model) {
                if ($model['type'] == 'parent_position_level') {
                    $employee->position->position->parents->where('level.value', $model['value'])->each(
                        fn($position) =>
                        $overtime->createApprovable(empty($model['hide_from_input']) ? $position->employeePositions()->active()->find($request->input('approvables.' . $index)) : $position->employeePositions()->active()->first())
                    );
                }
            }

            // Handle notifications
            if ($approvable = $overtime->approvables()->orderBy('level')->first()) {
                $approvable->userable->getUser()->notify(new SubmissionNotification($overtime, null));
              //  $approvable->userable->getUser()->notify(new AccountNotification($overtime->employee->user->name . ' mengajukan lembur ' . $overtime->name . ', silakan cek pada link berikut ' . route('portal::overtime.manage.show', ['overtime' => $overtime->id]), $approvable->userable->getUser()));
            } else {
                $overtime->update(['paidable_at' => now()]);
            }
            return redirect()->route('portal::overtime.submission.index')->with('success', isset($approvable) ? 'Pengajuan lembur sudah terkirim, silakan tunggu notifikasi selanjutnya dari atasan!' : 'Pengajuan sudah tersimpan dan sudah disetujui otomatis oleh sistem, terima kasih!');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeOvertime $overtime)
    {
        $overtime->delete();

        // Handle notifications
        if ($approvable = $overtime->approvables()->whereNotIn('result', [ApprovableResultEnum::PENDING])->orderBy('level')->first()) {
            $approvable->userable->employee->user->notify(new CanceledNotification($overtime));
        }

        return redirect()->route('portal::overtime.submission.index')->with('success', 'Pengajuan telah dibatalkan dan kami telah mengirim notifikasi ke atasan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function approve(EmployeeOvertime $overtime)
    {
        $overtime->fill(['accepted_at' => now()]);
        if ($overtime->save()) {
            return redirect()->back()->with('success', 'Anda telah menerima instruksi lembur, silakan melakukan lembur!');
        }
        return redirect()->fail();
    }

    public function export()
    {
        return 'ok';
    }
}
