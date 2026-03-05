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
        'label' => 'Pengelolaan',
    ],

    [
        'type'  => 'item',
        'label' => 'Asrama Siswa',
        'icon'  => 'home_work',
        'route' => route('boarding::facility.student.index'),
    ],

    [
        'type'  => 'item',
        'label' => 'Izin Siswa',
        'icon'  => 'logout',
        'route' => route('boarding::leave.manage.index'),
    ],

    [
        'type'  => 'item',
        'label' => 'Kegiatan Siswa',
        'icon'  => 'event_note',
        'route' => route('boarding::event.event-student.index'),
    ],

    [
        'type'  => 'title',
        'label' => 'Referensi',
    ],

    [
        'type'  => 'item',
        'label' => 'Daftar Kegiatan',
        'icon'  => 'calendar_month',
        'route' => route('boarding::event.event-reference.index'),
    ],
];
@endphp

@if(config('theme.default') == 'material')
    @include('layouts.component.material-admin-nav')
@elseif(config('theme.default') == 'skote')

@endif

