@extends('poz::layout.pos.index2')

@section('title', env('APP_NAME') . ' POS Sale')

@section('navtitle', env('APP_NAME') . ' POS Sale')

@section('content')
    @if (Session::has('msg-sukses'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
            <div class="alert alert-success">
                {{ Session::get('msg-sukses') }}
            </div>
        </div>
    @endif

    @if (Session::has('msg-gagal'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
            <div class="alert alert-danger">
                {{ Session::get('msg-gagal') }}
            </div>
        </div>
    @endif


    @livewire('poz::transaction.pos-sale', ['action' => ''])

@endsection
