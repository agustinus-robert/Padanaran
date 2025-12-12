@props([
    'item',
    'routes' => [],
])

@php
    $isTrashed = method_exists($item, 'trashed') ? $item->trashed() : false;
@endphp

<style>
    .btn-uniform {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        padding: 0;
        font-size: 14px;
    }

    .btn-flex {
        display: flex;
        align-items: center;
        justify-content: center;
        gap:4px;
    }
</style>

<div class="btn-flex">
    @if($isTrashed)
        @if(isset($routes['restore']))
            <form action="{{ route($routes['restore'], $item->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-primary btn-uniform" title="Pulihkan">
                    <i class="material-symbols-rounded">restore</i>
                </button>
            </form>
        @endif

        @if(isset($routes['kill']))
            <form action="{{ route($routes['kill'], $item->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-uniform" title="Hapus permanen">
                    <i class="material-symbols-rounded">delete_forever</i>
                </button>
            </form>
        @endif
    @else
        @if(isset($routes['show']))
            <a class="btn btn-outline-info btn-uniform m-0 py-2" href="{{ route($routes['show'], $item->id) }}" title="Detail">
                <i class="material-symbols-rounded">visibility</i>
            </a>
        @endif

        @if(isset($routes['edit']))
            <a class="btn btn-outline-warning btn-uniform m-0 py-2" href="{{ route($routes['edit'], $item->id) }}" title="Edit">
                <i class="material-symbols-rounded">edit</i>
            </a>
        @endif

        @if(isset($routes['destroy']))
            <form action="{{ route($routes['destroy'], $item->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-uniform m-0 py-2" title="Hapus">
                    <i class="material-symbols-rounded">delete</i>
                </button>
            </form>
        @endif
    @endif
</div>
