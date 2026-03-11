@extends('layouts.horizontal-layout')

@section('title', 'Perizinan - ')

@section('navtitle', 'Ijin siswa')

@section('breadcrumb')
    <li class="breadcrumb-item">Akademik</li>
    <li class="breadcrumb-item active">Izin Siswa</li>
@endsection

@push('nav')
@include('academic::layouts.includes.navbar-academic')
@endpush

@php
$trashed = false;
$columns = [
    [
        'field' => 'student.user.profile.name', 
        'label' => 'Nama Siswa',
        'slot' => fn($item) => $item->student->user->profile->name
    ],
    
    [
        'field' => 'category', 
        'label' => 'Kategori & Keterangan',
        'slot' => fn($item) => '<div>' . $item->category->name . '</div><small class="text-muted">' . $item->description . '</small>'
    ],
    
    [
        'field' => 'created_at', 
        'label' => 'Tgl. Pengajuan',
        'slot' => fn($item) => '<span class="small">' . $item->created_at->format('d M Y') . '</span>'
    ],
    
    [
        'field' => 'dates', 
        'label' => 'Tanggal Izin',
        'slot' => function($item) {
            $html = '';
            foreach (collect($item->dates)->take(3) as $date) {
                $strike = isset($date['c']) ? 'text-decoration-line-through' : '';
                $freelancer = isset($date['f']) ? '<i class="mdi mdi-account-network-outline text-danger"></i> ' : '';
                $time = isset($date['t_s']) ? ' pukul ' . $date['t_s'] : '';
                $time .= isset($date['t_e']) ? ' s.d. ' . $date['t_e'] : '';
                
                $html .= '<span class="badge bg-soft-secondary text-dark fw-normal mb-1 ' . $strike . '">' . 
                         $freelancer . date('d M Y', strtotime($date['d'])) . $time . 
                         '</span><br>';
            }
            $remain = collect($item->dates)->count() - 3;
            if ($remain > 0) {
                $html .= '<span class="badge text-dark fw-normal">+' . $remain . ' lainnya</span>';
            }
            return $html;
        }
    ],
    
    [
        'field' => 'status', 
        'label' => 'Status',
        'slot' => fn($item) => view('portal::leave.components.status', ['leave' => $item])->render()
    ],
    
    [
        'field' => 'actions', 
        'label' => 'Aksi',
        'slot' => function($item) {
            if ($item->trashed()) return '';

            $html = '<div class="text-end nowrap">';
            
            // Tombol Status Approval (Collapse)
            if ($item->hasApprovables()) {
                $html .= '<button class="btn btn-soft-primary btn-sm rounded px-2 py-1 me-1" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapse-' . $item->id . '" 
                            title="Status pengajuan">
                            <i class="mdi mdi-progress-clock"></i>
                          </button>';
            }

            // Dropdown Aksi
            $html .= '<div class="dropstart d-inline">
                        <button class="btn btn-soft-secondary text-dark btn-sm rounded px-2 py-1" type="button" data-bs-toggle="dropdown">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu border-0 shadow">
                            <li><a class="dropdown-item" href="' . route('boarding::leave.manage.show', ['leave' => $item->id, 'next' => request('next')]) . '"><i class="mdi mdi-eye-outline me-1"></i> Lihat detail</a></li>';
            
            if (isset($item->attachment) && \Storage::exists($item->attachment)) {
                $html .= '<li><a class="dropdown-item" href="' . \Storage::url($item->attachment) . '" target="_blank"><i class="mdi mdi-file-link-outline me-1"></i> Lihat lampiran</a></li>';
            }

            $html .= '<li><a class="dropdown-item" href="' . route('boarding::leave.print', ['leave' => $item->id]) . '" target="_blank"><i class="mdi mdi-printer-outline me-1"></i> Cetak dokumen (.pdf)</a></li>
                        </ul>
                    </div>';
            
            $html .= '</div>';
            return $html;
        }
    ]
];
@endphp

@push('additional-content')
    <div class="card border-0 shadow-none bg-gray-100 mb-3">
        <div class="list-group list-group-flush border-top">
            <a class="list-group-item list-group-item-action bg-transparent text-danger d-flex align-items-center py-3 text-sm" 
               href="{{ route('boarding::leave.manage.index', ['pending' => !request('pending')]) }}">
                <i class="material-symbols-rounded me-2 text-sm">filter_list</i> 
                {{ request('pending') == 1 ? 'Tampilkan semua pengajuan' : 'Hanya tampilkan yang tertunda' }}
            </a>
        </div>
    </div>

    {{-- 2. TOMBOL PENGAJUAN BARU --}}
    <div class="card shadow-sm border-radius-lg">
        <a href="{{ route('boarding::leave.submission.create', ['next' => url()->full()]) }}" 
           class="card-body p-3 d-flex justify-content-between align-items-center cursor-pointer transition-all">
            <div class="d-flex align-items-center">
                <div class="icon icon-shape icon-sm bg-gradient-primary shadow text-center border-radius-md me-3">
                    <i class="material-symbols-rounded opacity-10 text-xs">add_card</i>
                </div>
                <div>
                    <span class="d-block fw-bold text-dark text-sm mb-0">Pengajuan Izin Baru</span>
                    <p class="text-xxs text-muted mb-0">Klik di sini untuk membuat pengajuan</p>
                </div>
            </div>
            <i class="material-symbols-rounded text-primary opacity-7">arrow_circle_right</i>
        </a>
    </div>
@endpush

@include('components.navbar-admin')

@section('body-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <x-table
                    type="material"
                    :data="$leaves"
                    :columns="$columns"
                    title="Perizinan"
                    searchRoute="{{ route('boarding::leave.manage.index', ['academic' => request('academic')]) }}"
                    :trash="$trashed"
                    :count="$pending_leaves_count"
                    countLabel="Jumlah Perizinan"
                >
                    {{-- Slot untuk menampung baris collapse approval tepat di bawah tiap baris data --}}
                    @slot('after_row', function($item) {
                        if (!$item->hasApprovables() || $item->trashed()) return '';
                        
                        return view('boarding::leave.components.approval-table', ['leave' => $item])->render();
                    })
                </x-table>
            </div>
        </div>
    </div>
@endsection