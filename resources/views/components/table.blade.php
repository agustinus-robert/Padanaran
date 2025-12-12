@props([
    'type' => 'material',
    'data',             // Collection
    'columns' => [],    // Array of columns [{field,label,sortable,slot}]
    'title' => 'Tabel',
    'searchRoute' => '',
    'trash' => false,
])

@php
    $isEmpty = $data->isEmpty();
@endphp

@if($type === 'material')
<div class="card my-4">
    {{-- Header --}}
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3 m-0">{{ $title }}</h6>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="card-body px-3 pb-2">

        {{-- Search Form --}}
       @if($searchRoute)
    <form action="{{ $searchRoute }}" method="GET" class="mb-2 d-flex align-items-center"
        style="border:1px solid #ced4da; border-radius:6px; padding:2px;">
        <input type="hidden" name="trash" value="{{ $trash }}">
        <input type="text" name="search"
            value="{{ request('search') }}"
            placeholder="Cari..."
            style="border:none; outline:none; flex:1; padding:2px 6px; font-size:12px; background:transparent; border-radius:6px;">

        <button type="submit" title="Cari"
            style="width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; border:1px solid #007bff; background:#007bff; color:white; font-size:14px; margin-left:3px;">
            <i class="material-symbols-rounded" style="font-size:14px;">search</i>
        </button>

        <a href="{{ $searchRoute }}" title="Refresh"
            style="width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; border:1px solid #6c757d; background:white; color:#6c757d; font-size:14px; margin-left:3px;">
            <i class="material-symbols-rounded" style="font-size:14px;">refresh</i>
        </a>
    </form>
@endif
        {{-- Alerts --}}
        <div class="col-12 p-2">
            <div class="container">
                @if(Session::has('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                    <div class="alert alert-success">{!! Session::get('success') !!}</div>
                </div>
                @endif
                @if(Session::has('danger'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                    <div class="alert alert-danger">{!! Session::get('danger') !!}</div>
                </div>
                @endif
                @if($trash)
                <div class="alert alert-warning text-danger mt-3 mb-0">
                    <i class="mdi mdi-alert-circle-outline"></i> Menampilkan data yang dihapus
                </div>
                @endif
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">No</th>
                        @foreach($columns as $col)
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ $col['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @if($isEmpty)
                        <tr>
                            <td colspan="{{ count($columns)+1 }}" class="text-center py-4"><i>Tidak ada data</i></td>
                        </tr>
                    @else
                        @foreach($data as $index => $item)
                        <tr>
                            <td>{{ isset($data->firstItem) ? $data->firstItem() + $index : $loop->iteration }}</td>
                            @foreach($columns as $col)
                                <td class="align-items-center">
                                    @if(isset($col['slot']) && is_callable($col['slot']))
                                        {!! $col['slot']($item) !!}
                                    @else
                                        @php
                                            $value = data_get($item, $col['field']);
                                            if(is_object($value)) $value = method_exists($value,'name') ? $value->name : json_encode($value);
                                        @endphp
                                        {{ $value ?? '-' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>

@elseif($type === 'skote')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <i class="mdi mdi-account-details mr-2 float-left"></i>{{ $title }}
        @if($searchRoute)
        <form action="{{ $searchRoute }}" method="GET" class="d-flex">
            <input type="hidden" name="trash" value="{{ $trash }}">
            <input class="form-control form-control-sm me-2" name="search" type="text" placeholder="Cari..." value="{{ request('search') }}">
            <div class="input-group-append">
                <a class="btn btn-outline-secondary" href="{{ $searchRoute }}"><i class="mdi mdi-refresh"></i></a>
                <button class="btn btn-sm btn-primary">Cari</button>
            </div>
        </form>
        @endif
    </div>

    <div class="col-12 p-2">
        <div class="container">
            @if(Session::has('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                    <div class="alert alert-success">{!! Session::get('success') !!}</div>
                </div>
            @endif
            @if(Session::has('danger'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                    <div class="alert alert-danger">{!! Session::get('danger') !!}</div>
                </div>
            @endif
            @if($trash)
                <div class="alert alert-warning text-danger mt-3 mb-0">
                    <i class="mdi mdi-alert-circle-outline"></i> Menampilkan data yang dihapus
                </div>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0 border-bottom">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    @foreach($columns as $col)
                        <th>{{ $col['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if($isEmpty)
                    <tr>
                        <td colspan="{{ count($columns)+1 }}" class="text-center py-4"><i>Tidak ada data</i></td>
                    </tr>
                @else
                    @foreach($data as $index => $item)
                        <tr>
                            <td>{{ isset($data->firstItem) ? $data->firstItem() + $index : $loop->iteration }}</td>
                            @foreach($columns as $col)
                                <td>
                                    @if(isset($col['slot']) && is_callable($col['slot']))
                                        {!! $col['slot']($item) !!}
                                    @else
                                        @php
                                            $value = data_get($item, $col['field']);
                                            if(is_object($value)) $value = method_exists($value,'name') ? $value->name : json_encode($value);
                                        @endphp
                                        {{ $value ?? '-' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endif
