@extends('layouts.default')

@section('main')
    <table class="table-bordered table-hover table">
        <thead class="table-dark">
            <tr>
                <th rowspan="3">#</th>
                <th rowspan="3">Nama karyawan</th>
                <th rowspan="3">Jabatan</th>
            </tr>
            <tr>
                @foreach ($components->groupBy('category.name') as $name => $xxx)
                    <th colspan="{{ $xxx->count() }}">{{ $name }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($components as $component)
                    <th>{{ $component->id }} {{ $component->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                @php($items = $employee->salaryTemplates->first()?->items)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->user->name }}</td>
                    <td>{{ $employee->contract?->position?->position?->name ?? '-' }}</td>
                    @foreach ($components as $component)
                        <th>
                            @if ($item = $items?->firstWhere('component_id', $component->id))
                                {{ $item->amount >= 0 ? $item->amount : '' }}
                            @else
                            @endif
                        </th>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
