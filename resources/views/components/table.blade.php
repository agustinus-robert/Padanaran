@props([
    'type' => 'material',
    'data',             // Collection
    'columns' => [],    // Array of columns [{field,label,sortable,slot}]
    'title' => 'Tabel',
    'createRoute' => null, 
    'searchRoute' => '',
    'trash' => false,
    'isSearch' => true,
    'searchDynamic' => [], 
    'extra' => [],
    'extracollapse' => [],
    'count' => null,
    'countLabel' => 'Total Data'
])

@php
    $isEmpty = $data->isEmpty();
    $isPaginated = method_exists($data, 'links');
@endphp

@if($type === 'material')
<div class="card my-4">
    {{-- Header --}}
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3 m-0">{{ $title }}</h6>
            
            @if($createRoute)
                <div class="pe-3">
                    <a href="{{ $createRoute }}" class="btn btn-sm bg-gradient-info mb-0">
                        <i class="material-symbols-rounded text-sm">add</i>
                        <span class="ms-1">Tambah</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Card Body --}}
    <div class="card-body px-3 pb-2">
        
        {{-- Row Info & Search (SEJAJAR) --}}
        <div class="row align-items-center mb-3">
            {{-- KIRI: Count Info --}}
            <div class="col-md-4">
                @if($count !== null)
                    <div class="card border shadow-none bg-gray-100 mb-0">
                        <div class="card-body p-2">
                            {{-- Menggunakan d-flex agar icon dan teks sejajar secara horizontal --}}
                            <div class="d-flex align-items-center">
                                {{-- ICON DI KIRI --}}
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center border-radius-md" style="width: 30px; height: 38px; min-width: 32px;">
                                    <i class="material-symbols-rounded text-sm opacity-10">table_rows</i>
                                </div>
                                
                                {{-- TEKS DI KANAN ICON --}}
                                <div class="ps-3">
                                    <p class="text-xs mb-0 text-capitalize font-weight-bold opacity-7">{{ $countLabel }}</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $count }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- KANAN: Search Form --}}
            <div class="col-md-8 d-flex justify-content-end align-items-center">
                @if($searchRoute)
                    <form action="{{ $searchRoute }}" method="GET" class="d-flex align-items-center gap-2 mb-0 p-1" 
                          style="border:1px solid #ced4da; border-radius:6px; min-width: 300px;">
                        <input type="hidden" name="trash" value="{{ $trash }}">

                        {{-- DYNAMIC SELECTS (Jika ada) --}}
                        @if(count($searchDynamic) > 0)
                            <div class="d-flex gap-1 me-2 border-end pe-2">
                                @foreach($searchDynamic as $label => $name)
                                    <select name="{{ $name }}" class="form-select form-select-sm border-0 bg-transparent text-xs" style="width: auto;">
                                        <option value="">{{ ucfirst($label) }}</option>
                                    </select>
                                @endforeach
                            </div>
                        @endif

                        {{-- TEXT SEARCH --}}
                        @if($isSearch)
                            <input type="text" name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari..."
                                style="border:none; outline:none; flex:1; padding:2px 6px; font-size:12px; background:transparent;">

                            <button type="submit" title="Cari"
                                style="width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; border:none; background:#007bff; color:white; margin-left:3px;">
                                <i class="material-symbols-rounded" style="font-size:14px;">search</i>
                            </button>

                            <a href="{{ $searchRoute }}" title="Refresh"
                                style="width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; border:1px solid #6c757d; background:white; color:#6c757d; margin-left:3px;">
                                <i class="material-symbols-rounded" style="font-size:14px;">refresh</i>
                            </a>
                        @endif
                    </form>
                @endif
            </div>
        </div>

        {{-- Extra Slots Row --}}
        @if(count($extra) > 0)
            <div class="row col-12 g-2 p-2 mb-2">
                @foreach($extra as $slot)
                    <div class="col-auto">{!! $slot !!}</div>
                @endforeach
            </div>
        @endif

        {{-- Alerts --}}
        <div class="row px-2">
            <div class="container-fluid">
                @if(Session::has('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                    <div class="alert alert-success text-white py-2">{!! Session::get('success') !!}</div>
                </div>
                @endif
                @if(Session::has('danger'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                    <div class="alert alert-danger text-white py-2">{!! Session::get('danger') !!}</div>
                </div>
                @endif
                @if($trash)
                <div class="alert alert-warning text-dark mt-2 mb-0 py-2 border-warning">
                    <i class="material-symbols-rounded text-sm">report</i> Menampilkan data yang dihapus
                </div>
                @endif
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive p-0 mt-3">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">No</th>
                        @foreach($columns as $col)
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ $col['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @if($isEmpty)
                        <tr>
                            <td colspan="{{ count($columns)+1 }}" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 200px;">
                                    @include('components.notfound')
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($data as $index => $item)
                            <tr>
                                <td class="ps-3 text-sm">
                                    {{ $isPaginated ? ($data->firstItem() + $index) : ($loop->iteration) }}
                                </td>

                                @foreach($columns as $col)
                                    <td class="align-items-center text-sm">
                                        @php
                                            $value = '-';
                                            if(isset($col['field'])) {
                                                if(is_callable($col['field'])) {
                                                    $value = $col['field']($item);
                                                } else {
                                                    $value = data_get($item, $col['field'], '-');
                                                }
                                                if(is_object($value)) {
                                                    $value = method_exists($value, 'name') ? $value->name : json_encode($value);
                                                }
                                            }
                                        @endphp

                                        @if(isset($col['slot']) && is_callable($col['slot']))
                                            {!! $col['slot']($item) !!}
                                        @else
                                            {!! $value !!}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            @if(isset($extracollapse['row']) && is_callable($extracollapse['row']))
                                {!! $extracollapse['row']($item, count($columns) + 1) !!}
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        @if($isPaginated)
            <div class="d-flex justify-content-between align-items-center p-3">
                <p class="text-xs text-muted mb-0">Total: <b>{{ $data->total() }}</b> data</p>
                <div>
                    {!! $data->appends(request()->query())->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        @endif
    </div>
</div>

@elseif($type === 'skote')
{{-- BLOK SKOTE TETAP SAMA SEPERTI ASLINYA --}}
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
                        @if(isset($extracollapse['row']) && is_callable($extracollapse['row']))
                            {!! $extracollapse['row']($item, count($columns) + 1) !!}
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endif

<style>
    .gap-1 { gap: 0.25rem !important; }
    .gap-2 { gap: 0.5rem !important; }
</style>