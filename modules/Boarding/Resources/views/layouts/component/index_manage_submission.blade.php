@php
$trashed = request('trashed'); // Disinkronkan dengan state aplikasi jika perlu

$columns = [
    [
        'field' => 'employee',
        'label' => 'Karyawan',
        'slot' => fn ($item) => e($item->student->user->profile->name),
    ],
    [
        'field' => 'category',
        'label' => 'Kategori',
        'slot' => fn ($item) => "
            <div>" . e($item->category->name) . "</div>
            <small class='text-muted'>" . e($item->description) . "</small>
        ",
    ],
    [
        'field' => 'created_at',
        'label' => 'Tgl Pengajuan',
        'slot' => fn ($item) => '<span class="small">' . $item->created_at->formatLocalized('%d %B %Y') . '</span>',
    ],
    [
        'field' => 'leave_time',
        'label' => 'Waktu Izin',
        'slot' => function ($item) {
            $dates = collect($item->dates);
            $html = '';

            foreach ($dates->take(3) as $date) {
                $isCanceled = isset($date['c']) ? 'text-decoration-line-through' : '';
                $isFreelance = isset($date['f']);
                $timeInfo = (isset($date['t_s']) ? ' pukul ' . $date['t_s'] : '') . (isset($date['t_e']) ? ' s.d. ' . $date['t_e'] : '');

                $html .= "<span class='badge bg-soft-secondary text-dark fw-normal user-select-none {$isCanceled}'>";
                if ($isFreelance) $html .= '<i class="mdi mdi-account-network-outline text-danger"></i> ';
                $html .= strftime('%d %B %Y', strtotime($date['d'])) . $timeInfo;
                $html .= "</span> ";
            }

            if (($remain = $dates->count() - 3) > 0) {
                $html .= "<span class='badge text-dark fw-normal user-select-none'>+{$remain} lainnya</span>";
            }

            return "<div style='min-width: 200px;'>{$html}</div>";
        },
    ],
    [
        'field' => 'attachment',
        'label' => 'Lampiran',
        'class' => 'text-center',
        'slot' => function ($item) {
            if (isset($item->attachment) && Storage::exists($item->attachment)) {
                return '<a class="btn btn-soft-dark btn-sm rounded px-2 py-1" href="' . Storage::url($item->attachment) . '" target="_blank"><i class="mdi mdi-file-link-outline"></i></a>';
            }
            return '-';
        },
    ],
    [
        'field' => 'status',
        'label' => 'Status',
        'slot' => fn ($item) => view('portal::leave.components.status', ['leave' => $item])->render(),
    ],
    [
        'field' => 'actions',
        'label' => '',
        'class' => 'text-end',
        'slot' => fn ($item) => $item->trashed() ? '' : view('portal::leave.components.actions-dropdown', [
            'leave' => $item,
            'module' => $module
        ])->render(),
    ],
];
@endphp

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-xl-8">
            <x-table
                type="material"
                title="Data Pengajuan Izin"
                :isSearch="false"
                :data="$leaves"
                :columns="$columns"
                :trash="$trashed"
                searchRoute="{{ route($module . '::leave.manage.index') }}"
                :extra="[view('boarding::layouts.component.extra-filter', ['module' => $module])->render()]"
            />
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h6>Pengajuan Tertunda</h6>
                </div>

                <div class="card-body d-flex justify-content-between align-items-center py-4">
                    <div>
                        <div class="display-5 fw-bold">{{ $pending_leaves_count }}</div>
                        <div class="small fw-bold text-secondary text-uppercase">Total</div>
                    </div>
                    <div class="bg-soft-danger p-3 rounded-circle">
                        <i class="mdi mdi-timer-outline mdi-36px text-danger"></i>
                    </div>
                </div>

                <div class="list-group list-group-flush border-top">
                    <a class="list-group-item list-group-item-action py-3 {{ request('pending') ? 'bg-light fw-bold' : 'text-danger' }}"
                       href="{{ route($module.'::leave.manage.index', ['pending' => !request('pending')]) }}">
                        <i class="mdi {{ request('pending') ? 'mdi-filter-off' : 'mdi-filter-variant' }} me-1"></i>
                        {{ request('pending') == 1 ? 'Tampilkan semua pengajuan' : 'Hanya tampilkan yang tertunda' }}
                    </a>
                </div>
            </div>

            {{-- Slot untuk Tombol Tambah (jika diperlukan nantinya) --}}
            <div class="mt-3 text-muted small px-2">
                <i class="mdi mdi-information-outline"></i> Gunakan filter untuk mencari data spesifik berdasarkan status atau tanggal.
            </div>
        </div>
    </div>
</div>
