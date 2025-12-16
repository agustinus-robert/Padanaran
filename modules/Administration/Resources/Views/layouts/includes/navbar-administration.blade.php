@php
$menus = [

    [
        'type'  => 'item',
        'label' => 'Dasbor',
        'icon'  => 'dashboard',
        'route' => route('administration::dashboard'),
    ],

    [
        'type'  => 'title',
        'label' => 'Administrasi',
    ],

    [
        'type'  => 'dropdown',
        'label' => 'Kesiswaan',
        'icon'  => 'groups',
        'children' => [
            [
                'label' => 'Rombel',
                'icon'  => 'group',
                'route' => route('administration::scholar.classrooms.index'),
            ],
            [
                'label' => 'Data siswa',
                'icon'  => 'groups_2',
                'route' => route('administration::scholar.students.index'),
            ],
            [
                'label' => 'Registrasi semester',
                'icon'  => 'checklist',
                'route' => route('administration::scholar.semesters.index'),
            ],
        ]
    ],

    [
        'type' => 'dropdown',
        'label' => 'Kurikulum',
        'icon'  => 'menu_book',
        'children' => [
            [
                'label' => 'Mapel',
                'icon'  => 'book',
                'route' => route('administration::curriculas.subjects.index'),
            ],
            [
                'label' => 'Pertemuan',
                'icon'  => 'event_note',
                'route' => route('administration::curriculas.meets.index'),
            ],
        ]
    ],

    [
        'type' => 'dropdown',
        'label' => 'Sarpras',
        'icon'  => 'business',
        'children' => [
            [
                'label' => 'Gedung',
                'icon'  => 'business',
                'route' => route('administration::facility.buildings.index'),
            ],
            [
                'label' => 'Ruang',
                'icon'  => 'meeting_room',
                'route' => route('administration::facility.rooms.index'),
            ],
        ]
    ],

    [
        'type' => 'item',
        'label' => auth()->user()->employee->grade_id == 4
            ? 'Tagihan Siswa SMP'
            : 'Tagihan Siswa SMA',
        'icon'  => 'school',
        'route' => auth()->user()->employee->grade_id == 4
            ? route('administration::bill.students.index', ['education' => 4])
            : route('administration::bill.students.index', ['education' => 5]),
    ],

    [
        'type' => 'title',
        'label' => 'Basis data',
    ],

    [
        'type' => 'item',
        'label' => 'Tahun ajaran',
        'icon'  => 'calendar_month',
        'route' => route('administration::database.academics.index'),
    ],

    [
        'type' => 'item',
        'label' => 'Data kurikulum',
        'icon'  => 'menu_book',
        'route' => route('administration::database.curriculas.index'),
    ],

    [
        'type' => 'item',
        'label' => 'Referensi Tagihan',
        'icon'  => 'playlist_add',
        'route' => route('administration::bill.references.index'),
    ],

    [
        'type' => 'item',
        'label' => 'Gelombang',
        'icon'  => 'waves',
        'route' => route('administration::bill.batchs.index'),
    ]
];
@endphp

@if(config('theme.default') == 'material')
    @include('layouts.component.material-admin-nav')
@elseif(config('theme.default') == 'skote')

@endif

