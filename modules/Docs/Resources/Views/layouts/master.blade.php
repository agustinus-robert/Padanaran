@extends('layouts.default')

@section('titleTemplate', config('modules.docs.name'))

@section('bodyclass', 'd-flex flex-column justify-content-between min-vh-100 bg-light')

@section('main')
    <div>
        @include('docs::layouts.components.navbar')
        <div id="app" class="py-sm-4 animate__animated animate__fadeIn animate__faster py-3">
            <main>
                <div class="container-lg">
                    <x-alert-success></x-alert-success>
                    <x-alert-danger></x-alert-danger>
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
@endsection
