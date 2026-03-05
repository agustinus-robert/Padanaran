@php
    $menus = [
        [
            'type'  => 'item',
            'label' => 'Beranda',
            'icon'  => 'speed', 
            'route' => route('academic::home'),
            'active' => true
        ],

        [
            'type'  => 'title',
            'label' => 'Kelas dan Asrama',
        ],

        [
            'type'  => 'item',
            'label' => 'Kelas Saya',
            'icon'  => 'meeting_room', 
            'route' => route('academic::classroom.index'),
            'active' => true
        ],

        [
            'type'  => 'item',
            'label' => 'Asrama',
            'icon'  => 'apartment', 
            'route' => route('academic::boarding.index'),
            'active' => true
        ],

        [
            'type'  => 'title',
            'label' => 'Pengelolaan',
            'active' => session('login_as_nik') 
        ],

        [
            'type'  => 'item',
            'label' => 'Perizinan',
            'icon'  => 'verified_user', 
            'route' => route('academic::leave.manage.index'),
            'active' => session('login_as_nik')
        ],

        [
            'type'  => 'title',
            'label' => 'Laporan',
        ],

        [
            'type'  => 'item',
            'label' => 'Aktivitas',
            'icon'  => 'directions_run', // Pengganti bx-run
            'route' => route('academic::activity.index'),
            'active' => true
        ],

        [
            'type'  => 'item',
            'label' => 'Konseling',
            'icon'  => 'record_voice_over', // Pengganti bxs-user-voice
            'route' => route('academic::counselings.index'),
            'active' => true
        ],

        [
            'type'  => 'item',
            'label' => 'Raport',
            'icon'  => 'assessment', // Pengganti bxs-report
            'route' => route('academic::report'),
            'active' => true
        ],
    ];
@endphp

@if(config('theme.default') == 'material')
    @include('layouts.component.material-admin-nav', ['menus' => $menus])
@elseif(config('theme.default') == 'skote')
    @include('layouts.component.skote-admin-nav', ['menus' => $menus])
@endif