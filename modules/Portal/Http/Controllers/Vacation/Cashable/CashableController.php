<?php

namespace Modules\Portal\Http\Controllers\Vacation\Cashable;

use Illuminate\Http\Request;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Vacation\Cashable\Cashable\StoreRequest;
use Modules\Portal\Notifications\Vacation\Submission\SubmissionNotification;

class CashableController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employee = $request->user()->employee;

        $quotas = $employee->vacationQuotas()->with('category', 'vacations')->active()->get()->filter(fn ($quota) => $quota->category->meta->cashable_limit ?? false);

        if (!$quotas->count()) {
            return redirect()->back()->with('danger', 'Maaf, sepertinya belum ada kuota cuti tahun ini nih, silakan hubungi admin untuk keterangan lebih lanjut!');
        }

        return view('portal::vacation.cashable.create', compact('employee', 'quotas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $employee = $request->user()->employee;

        $vacation = $employee->vacations()->create($request->transformed()->toArray());

        // Assign approvable though employee positions
        foreach (config('modules.core.features.services.vacations.approvable_steps', []) as $model) {
            if ($model['type'] == 'parent_position_level') {
                if ($position = $employee->position->position->parents->firstWhere('level.value', $model['value'])?->employeePositions()->active()->first()) {
                    $vacation->createApprovable($position);
                }
            }
        }

        // Handle notifications
        if ($approvable = $vacation->approvables()->orderBy('level')->first()) {
            $approvable->userable->getUser()->notify(new SubmissionNotification($vacation, true, null));
        }

        $employee->dataRecapitulations()->create([
            'type' => DataRecapitulationTypeEnum::CASHABLE_VACATION,
            'start_at' => '2022-11-21',
            'end_at' => '2022-12-10',
            'result' => [
                'days' => 5,
                'quota' => $vacation->quota
            ]
        ]);

        return redirect()->route('portal::home')->with('success', isset($position) ? 'Pengajuan kompensasi cuti sudah terkirim, silakan tunggu notifikasi selanjutnya dari atasan!' : 'Pengajuan kompensasi cuti sudah tersimpan dan sudah disetujui otomatis oleh sistem, terima kasih!');
    }
}
