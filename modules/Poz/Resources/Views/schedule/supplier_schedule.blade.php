@extends('poz::layout.index')

@section('title', env('APP_NAME') . ' Supplier')

@section('navtitle', env('APP_NAME') . ' Supplier')

@section('content')

    @if (str_contains(url()->full(), 'create') || str_contains(url()->full(), 'edit'))
        @livewire('poz::master.brand', ['action' => $action])
    @else
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Daftar Shift Supplier
                    <span class="d-block text-muted pt-2 font-size-sm">Pilih shift untuk mengelola jadwal</span></h3>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($schedules as $item)
                        @php
                            $icon = 'bx-time-five';
                            $color = 'primary';
                            if($item->key == 'morning') { $icon = 'bx-sun'; $color = 'warning'; }
                            if($item->key == 'afternoon') { $icon = 'bx-cloud-light-rain'; $color = 'info'; }
                            if($item->key == 'evening') { $icon = 'bx-moon'; $color = 'dark'; }
                        @endphp
                        
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                            <div class="card card-custom gutter-b card-stretch shadow-sm border">
                                <div class="card-body pt-6">
                                    <div class="d-flex align-items-center mb-7">
                                        <div class="symbol symbol-80 symbol-2by3 mr-5">
                                            <div class="symbol-label rounded-lg bg-light-{{ $color }}">
                                                <i class="bx {{ $icon }} font-size-h1 text-{{ $color }}" style="font-size: 3rem !important;"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex flex-column">
                                            <a href="{{ $item->url }}" class="text-dark font-weight-bold text-hover-primary font-size-h4 mb-0">
                                                {{ $item->label }}
                                            </a>
                                            <span class="text-muted font-weight-bold">Shift Kerja</span>
                                        </div>
                                    </div>

                                    <div class="bg-gray-100 p-4 rounded-xl mb-7">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-dark-75 font-weight-bold mr-2">Total Supplier</span>
                                            <span class="text-{{ $color }} font-weight-boldest font-size-h3">{{ $item->total_supplier }}</span>
                                        </div>
                                    </div>

                                    <a href="{{ $item->url }}" class="btn btn-block btn-light-{{ $color }} font-weight-bolder text-uppercase py-3">
                                        <i class="bx bx-edit-alt mr-1"></i> Manage Schedule
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection