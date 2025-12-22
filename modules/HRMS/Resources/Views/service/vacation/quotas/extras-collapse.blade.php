<tr>
    <td class="border-0 p-0" colspan="6">
        <div class="collapse" id="collapse-{{ $employee->id }}">
            <div class="collapse" id="collapse-{{ $employee->id }}">
                <table class="table-borderless table-hover table-sm mb-0 table align-middle">
                    <thead>
                        <tr class="text-muted small bg-light">
                            <th class="border-bottom fw-normal">Kategori</th>
                            <th class="border-bottom fw-normal">Masa berlaku</th>
                            <th class="border-bottom fw-normal">Kuota</th>
                            <th class="border-bottom fw-normal">Sisa</th>
                            <th class="border-bottom fw-normal">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->vacationQuotas as $quota)
                            <tr>
                                <td>{{ $quota->category->name }}</td>
                                <td>{{ $quota->start_at->isoFormat('LL') }} <span class="text-muted">s.d.</span> {{ $quota->end_at?->isoFormat('LL') ?: '∞' }}</td>
                                <td>{{ $quota->quota ?: '∞' }} hari</td>
                                <td>{{ is_null($quota->quota) ? '∞' : abs($quota->quota - $quota->vacations->sum(fn($vacation) => count($vacation->dates))) }} hari</td>
                                <td class="py-2 pe-2 text-end" nowrap>
                                    @can('destroy', $quota)
                                        <form class="form-block form-confirm d-inline" action="{{ route('hrms::service.vacation.quotas.destroy', ['quota' => $quota->id, 'next' => url()->current()]) }}" method="post"> @csrf @method('delete')
                                            <button class="btn btn-danger rounded px-2 py-1" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="material-symbols-rounded" style="font-size:14px;">delete</i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">Tidak ada kuota cuti yang didistribusikan @can('store', Modules\HRMS\Models\EmployeeVacationQuota::class)
                                        , <a href="{{ route('hrms::service.vacation.quotas.create', ['employee' => $employee->id, 'next' => url()->current()]) }}">klik di sini</a> untuk menambahkan
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </td>
</tr>
