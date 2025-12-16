@php
$menus = [

    [
        'type'  => 'title',
        'label' => 'Utama',
    ],
    [
        'type'  => 'item',
        'label' => 'Dasbor',
        'icon'  => 'dashboard',
        'route' => route('core::dashboard'),
    ],

    [
        'type'  => 'title',
        'label' => 'Perusahaan',
    ],

    [
        'type'  => 'item',
        'label' => 'Peran',
        'icon'  => 'security',
        'route' => route('core::company.roles.index'),
        'can'   => ['access', \Modules\Core\Models\CompanyRole::class],
    ],

    [
        'type'  => 'item',
        'label' => 'Divisi',
        'icon'  => 'account_tree',
        'route' => route('core::company.departments.index'),
    ],

    [
        'type'  => 'item',
        'label' => 'Jabatan',
        'icon'  => 'sell',
        'route' => route('core::company.positions.index'),
    ],

    [
        'type'  => 'dropdown',
        'label' => 'Layanan karyawan',
        'icon'  => 'support_agent',
        'children' => [
            [
                'label' => 'Kategori izin siswa',
                'icon'  => 'school',
                'route' => route('core::company.services.leave-student-categories.index'),
            ],
            [
                'label' => 'Kategori izin',
                'icon'  => 'event_busy',
                'route' => route('core::company.services.leave-categories.index'),
            ],
            [
                'label' => 'Kategori cuti',
                'icon'  => 'beach_access',
                'route' => route('core::company.services.vacation-categories.index'),
            ],
            [
                'label' => 'Kategori insentif',
                'icon'  => 'paid',
                'route' => route('core::company.services.outwork-categories.index'),
            ],
        ],
    ],

    [
        'type'  => 'dropdown',
        'label' => 'Asuransi',
        'icon'  => 'health_and_safety',
        'children' => [
            [
                'label' => 'Kelola asuransi',
                'icon'  => 'assignment',
                'route' => route('core::company.insurances.manages.index'),
            ],
        ],
    ],

    [
        'type'  => 'dropdown',
        'label' => 'Penggajian',
        'icon'  => 'payments',
        'children' => [
            [
                'label' => 'Slip gaji',
                'icon'  => 'receipt_long',
                'route' => route('core::company.salaries.slips.index'),
            ],
            [
                'label' => 'Kategori gaji',
                'icon'  => 'category',
                'route' => route('core::company.salaries.categories.index'),
            ],
            [
                'label' => 'Komponen gaji',
                'icon'  => 'tune',
                'route' => route('core::company.salaries.components.index'),
            ],
            [
                'label' => 'Template slip gaji',
                'icon'  => 'description',
                'route' => route('core::company.salaries.templates.index'),
            ],
            [
                'label' => 'Pengaturan slip gaji',
                'icon'  => 'settings',
                'route' => route('core::company.salaries.configs.index'),
            ],
        ],
    ],

    [
        'type'  => 'title',
        'label' => 'Sistem',
    ],

    [
        'type'  => 'dropdown',
        'label' => 'Pengguna',
        'icon'  => 'groups',
        'children' => [
            [
                'label' => 'Kelola pengguna',
                'icon'  => 'manage_accounts',
                'route' => route('core::system.users.index'),
            ],
            [
                'label' => 'Log',
                'icon'  => 'history',
                'route' => route('core::system.user-logs.index'),
            ],
        ],
    ],

    [
        'type'  => 'dropdown',
        'label' => 'Pengaturan',
        'icon'  => 'settings',
        'children' => [
            [
                'label' => 'Hari libur',
                'icon'  => 'event',
                'route' => route('core::company.moments.index'),
            ],
            [
                'label' => 'Jenjang Pendidikan',
                'icon'  => 'school',
                'route' => route('core::admin-extra.choose.extra-education'),
                'if'    => auth()->user()->roles()->first()?->id == 1,
            ],
            [
                'label' => 'Lainnya',
                'icon'  => 'more_horiz',
                'route' => 'javascript:void(0)',
            ],
        ],
    ],

    [
        'type'  => 'title',
        'label' => 'Akun',
    ],
    [
        'type'  => 'item',
        'label' => 'Akun saya',
        'icon'  => 'person',
        'route' => route('account::user.profile'),
    ],
    [
        'type'  => 'item',
        'label' => 'Ubah sandi',
        'icon'  => 'lock',
        'route' => route('account::user.password', ['next' => url()->full()]),
    ],
];
@endphp


@if(config('theme.default') == 'material')
    @include('layouts.component.material-admin-nav')
@elseif(config('theme.default') == 'skote')

@endif

