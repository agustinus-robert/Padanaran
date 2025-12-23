@php
$menus = [
    [
        'type'  => 'title',
        'label' => 'Utama',
    ],
    [
        'type'  => 'item',
        'label' => 'Beranda',
        'icon'  => 'speed', // Material Icons
        'route' => route('counseling::home'),
    ],

    [
        'type'  => 'title',
        'label' => 'Pengelolaan',
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Presensi',
        'icon'  => 'event_available',
        'children' => [
            [
                'label' => 'Presensi pagi',
                'icon'  => 'assignment',
                'route' => route('counseling::presences.create'),
            ],
            [
                'label' => 'Presensi mapel',
                'icon'  => 'assignment',
                'route' => route('counseling::presences.index'),
            ],
        ],
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Kasus',
        'icon'  => 'assignment_turned_in',
        'children' => [
            [
                'label' => 'Data kasus',
                'icon'  => 'list_alt',
                'route' => route('counseling::cases.index'),
            ],
            [
                'label' => 'Input kasus baru',
                'icon'  => 'add_box',
                'route' => route('counseling::cases.create'),
            ],
            [
                'label' => 'Kelola kategori',
                'icon'  => 'category',
                'route' => route('counseling::manage.cases.categories.index'),
            ],
            [
                'label' => 'Kelola deskripsi',
                'icon'  => 'description',
                'route' => route('counseling::manage.cases.descriptions.index'),
            ],
        ],
    ],
    [
        'type'  => 'dropdown',
        'label' => 'Konseling',
        'icon'  => 'record_voice_over',
        'children' => [
            [
                'label' => 'Data konseling',
                'icon'  => 'person',
                'route' => route('counseling::counselings.index'),
            ],
            [
                'label' => 'Input konseling baru',
                'icon'  => 'person_add',
                'route' => route('counseling::counselings.create'),
            ],
            [
                'label' => 'Kelola kategori',
                'icon'  => 'category',
                'route' => route('counseling::manage.counseling.categories.index'),
            ],
        ],
    ],

    /* ================= Siswa ================= */
    [
        'type'  => 'title',
        'label' => 'Siswa',
    ],
    [
        'type'  => 'item',
        'label' => 'Perizinan',
        'icon'  => 'healing',
        'route' => route('counseling::leave.manage.index'),
    ],
];
@endphp

@if(config('theme.default') == 'material')
    @include('layouts.component.material-admin-nav')
@elseif(config('theme.default') == 'skote')
    {{-- Skote layout nav --}}
@endif
