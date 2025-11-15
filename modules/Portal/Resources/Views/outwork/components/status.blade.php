@if ($outwork->trashed())
    <div class="badge bg-soft-danger text-danger fw-normal"><i class="mdi mdi-trash-can-outline"></i> Dihapus</div>
@else
    @if ($outwork->paid_off_at)
        <div class="badge bg-soft-secondary text-dark fw-normal"><i class="mdi mdi-cash-check"></i> Sudah dibayar</div>
    @else
        @if ($outwork->hasApprovables())
            @if ($outwork->approvableTypeIs('approvable'))
                @if ($outwork->hasAllApprovableResultIn('APPROVE'))
                    <div class="badge bg-soft-success text-success fw-normal"><i class="mdi mdi-exit-to-app"></i> Pengajuan disetujui</div>
                @elseif ($outwork->hasAnyApprovableResultIn('REJECT'))
                    <div class="badge bg-soft-danger text-danger fw-normal"><i class="mdi mdi-exit-to-app"></i> Pengajuan ditolak</div>
                @else
                    <div class="badge bg-soft-primary text-primary fw-normal"><i class="mdi mdi-exit-to-app"></i> Pengajuan</div>
                @endif
            @else
                <div class="badge bg-soft-warning text-warning fw-normal"><i class="mdi mdi-progress-upload"></i> Pembatalan</div>
            @endif
        @else
            <div class="badge bg-soft-success text-success fw-normal"><i class="mdi mdi-check"></i> Disetujui</div>
        @endif
    @endif
@endif
