@if ($overtime->trashed())
    <div class="badge bg-soft-danger text-danger fw-normal"><i class="mdi mdi-trash-can-outline"></i> Dihapus</div>
@else
    @if ($overtime->hasApprovables())
        @if ($overtime->approvableTypeIs('approvable'))
            @if ($overtime->hasAllApprovableResultIn('APPROVE'))
                <div class="badge bg-soft-success text-success fw-normal"><i class="mdi mdi-exit-to-app"></i> Pengajuan disetujui</div>
            @elseif ($overtime->hasAnyApprovableResultIn('REJECT'))
                <div class="badge bg-soft-danger text-danger fw-normal"><i class="mdi mdi-exit-to-app"></i> Pengajuan ditolak</div>
            @else
                <div class="badge bg-soft-primary text-primary fw-normal"><i class="mdi mdi-exit-to-app"></i> Pengajuan</div>
            @endif
        @else
            <div class="badge bg-soft-warning text-warning fw-normal"><i class="mdi mdi-progress-upload"></i> Pembatalan</div>
        @endif
    @else
        <div class="badge bg-soft-secondary text-secondary fw-normal"><i class="mdi mdi-check"></i> Instruksi</div>
    @endif
@endif
