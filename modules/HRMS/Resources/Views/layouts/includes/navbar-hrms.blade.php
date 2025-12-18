@php
$menus = [

    /* ================= Utama ================= */
    [
        'type'  => 'title',
        'label' => 'Utama',
    ],
    [
        'type'  => 'item',
        'label' => 'Dasbor',
        'icon'  => 'apps',
        'route' => route('hrms::dashboard'),
    ],

    /* ================= Karyawan ================= */
    [
        'type'  => 'title',
        'label' => 'Karyawan',
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Data Karyawan',
        'icon'  => 'account_box',
        'children' => [
            [
                'label' => 'Tambah guru',
                'icon'  => 'add',
                'route' => route(
                    'hrms::employment.employees.create',
                    ['next' => route('hrms::employment.employees.index')]
                ),
            ],
            [
                'label' => 'Kelola karyawan',
                'icon'  => 'manage_accounts',
                'route' => route('hrms::employment.employees.index'),
            ],
        ],
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Perjanjian kerja',
        'icon'  => 'description',
        'children' => [
            [
                'label' => 'Buat baru',
                'icon'  => 'add_circle',
                'route' => route(
                    'hrms::employment.contracts.create',
                    ['next' => route('hrms::employment.contracts.index')]
                ),
            ],
            [
                'label' => 'Data perjanjian kerja',
                'icon'  => 'list_alt',
                'route' => route('hrms::employment.contracts.index'),
            ],
        ],
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Jam Mengajar',
        'icon'  => 'calendar_month',
        'children' => [
            [
                'label' => 'Kelola',
                'icon'  => 'settings',
                'route' => route('hrms::service.teacher.schedule.index'),
            ],
        ],
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Petugas Piket',
        'icon'  => 'event_available',
        'children' => [
            [
                'label' => 'Kelola',
                'icon'  => 'settings',
                'route' => route('hrms::service.teacher.duty.index'),
            ],
        ],
    ],

    /* ================= Layanan ================= */
    [
        'type'  => 'title',
        'label' => 'Layanan',
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Presensi',
        'icon'  => 'fingerprint',
        'children' => [
            [
                'label' => 'Jadwal kerja',
                'icon'  => 'schedule',
                'route' => route('hrms::service.attendance.schedules.index'),
            ],
            [
                'label' => 'Kelola presensi',
                'icon'  => 'fact_check',
                'route' => route('hrms::service.attendance.manage.index'),
            ],
            [
                'label' => 'Daftar scanlog',
                'icon'  => 'list',
                'route' => route('hrms::service.attendance.scanlogs.index'),
            ],
        ],
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Cuti',
        'icon'  => 'event_busy',
        'children' => [
            [
                'label' => 'Distribusi kuota',
                'icon'  => 'pie_chart',
                'route' => route('hrms::service.vacation.quotas.index'),
            ],
            [
                'label' => 'Kelola cuti',
                'icon'  => 'edit_calendar',
                'route' => route('hrms::service.vacation.manage.index'),
            ],
        ],
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Izin',
        'icon'  => 'logout',
        'children' => [
            [
                'label' => 'Kelola izin',
                'icon'  => 'settings',
                'route' => route('hrms::service.leave.manage.index'),
            ],
        ],
    ],

    /* ================= Rekapitulasi ================= */
    [
        'type'  => 'title',
        'label' => 'Rekapitulasi',
    ],
    [
        'type'  => 'item',
        'label' => 'Kehadiran',
        'icon'  => 'event_available',
        'route' => route('hrms::summary.attendances.index'),
    ],
    [
        'type'  => 'item',
        'label' => 'Pengajaran',
        'icon'  => 'school',
        'route' => route('hrms::summary.teachings.index'),
    ],

    /* ================= Penggajian ================= */
    [
        'type'  => 'title',
        'label' => 'Penggajian',
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Penggajian',
        'icon'  => 'payments',
        'children' => [
            [
                'label' => 'Persetujuan',
                'icon'  => 'task_alt',
                'route' => route('hrms::payroll.approvals.index'),
            ],
        ],
    ],

    /* ================= Pelaporan ================= */
    [
        'type'  => 'title',
        'label' => 'Pelaporan',
    ],
    [
        'type'  => 'item',
        'label' => 'Karyawan',
        'icon'  => 'badge',
        'route' => route('hrms::report.employees.index'),
    ],
    [
        'type'  => 'item',
        'label' => 'Kehadiran',
        'icon'  => 'fact_check',
        'route' => route('hrms::report.attendances.index'),
    ],
    [
        'type'  => 'item',
        'label' => 'Cuti',
        'icon'  => 'event_busy',
        'route' => route('hrms::report.vacations.index'),
    ],
    [
        'type'  => 'item',
        'label' => 'Izin',
        'icon'  => 'schedule',
        'route' => route('hrms::report.leaves.index'),
    ],
    [
        'type'  => 'item',
        'label' => 'Penggajian',
        'icon'  => 'account_balance_wallet',
        'route' => route('hrms::report.salaries.index'),
    ],
];
@endphp

@if(config('theme.default') == 'material')
    @include('layouts.component.material-admin-nav')
@elseif(config('theme.default') == 'skote')

@endif
