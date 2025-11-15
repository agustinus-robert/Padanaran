<div class="card border-0">
    <div class="card-body">
        <i class="mdi mdi-account-box-multiple-outline"></i> Detail karyawan
    </div>
    <div class="list-group list-group-flush border-top">
        @foreach (array_filter([
        'Nama karyawan' => $employee->user->name,
        'NIP' => $employee->kd ?: '-',
        'Jabatan' => $employee->position->position->name ?? '-',
        'Departemen' => $employee->position->position->department->name ?? '-',
        'Manajer' => $employee->position->position->parents->firstWhere('level.value', 4)?->employees->first()->user->name,
    ]) as $label => $value)
            <div class="list-group-item">
                <div class="row d-flex align-items-center">
                    <div class="col-sm-6 col-xl-12">
                        <div class="small text-muted">{{ $label }}</div>
                    </div>
                    <div class="col-sm-6 col-xl-12 fw-bold"> {{ $value }} </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
