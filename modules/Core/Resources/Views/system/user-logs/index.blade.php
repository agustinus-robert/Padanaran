@extends('layouts.horizontal-layout')

@section('title', 'Log | ')
@section('navtitle', 'Log')

@section('bodyclass', 'app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show')

@push('nav')
    @include('core::layouts.includes.navbar-core')
@endpush

@php
    $trashed = false;
    $columns = [
        [
            'label' => 'Pengguna',
            'slot'  => fn ($log) => "<strong>{$log->user->name}</strong>",
            'class' => 'fw-bold',
        ],
        [
            'label' => 'Log',
            'slot'  => fn ($log) => $log->message,
            'escape' => false, // kalau table component support
        ],
        [
            'label' => 'IP',
            'slot'  => fn ($log) => "<span class='text-muted'>{$log->ip}</span>",
        ],
        [
            'label' => 'UA',
            'slot'  => fn ($log) => "<span class='text-muted'>{$log->user_agent}</span>",
        ],
        [
            'label' => 'Waktu',
            'slot'  => fn ($log) => $log->created_at,
            'class' => 'text-nowrap',
        ],
        [
            'label' => 'Aksi',
            'slot'  => fn ($log) =>
                auth()->user()->can('destroy', $log)
                    ? view('components.partial-actions', [
                        'item' => $log,
                        'routes' => [
                            'destroy' => 'core::system.user-logs.destroy',
                        ],
                        'trashed' => false,
                        'useModal' => false,
                        'next' => url()->current(),
                    ])->render()
                    : '',
            'class' => 'text-end',
        ],
    ];
@endphp

@section('body-content')
    @include('components.navbar-admin')

    <div class="container-fluid">
        <x-table
            :isSearch="false"
            type="material"
            :data="$logs"
            :columns="$columns"
            title="Daftar Log"
            :trash="$trashed"
            {{-- :extra="[view('core::system.user-logs.extra-filter')->render()]" --}}
            :count="count($logs)"
            countLabel="Jumlah Logs"
        />
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/css/tom-select.bootstrap5.min.css') }}">
    <style type="text/css">
        .ts-wrapper {
            padding: 0 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        new TomSelect('[name="user"]', {
            valueField: 'id',
            labelField: 'text',
            searchField: 'text',
            placeholder: 'Cari pengguna disini ...',
            load: function(q, callback) {
                fetch('{{ route('api::account.users.search') }}?q=' + encodeURIComponent(q))
                    .then(response => response.json())
                    .then(json => {
                        callback(json.users);
                    }).catch(() => {
                        callback();
                    });
            }
        });
    </script>
@endpush
