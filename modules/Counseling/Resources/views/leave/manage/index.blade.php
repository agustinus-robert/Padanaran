@extends('layouts.horizontal-layout')

@section('title', 'Kelola pengajuan | ')

@push('nav')
    @include('counseling::layouts.includes.navbar-counseling')
@endpush

@section('body-content')
    @include('components.navbar-admin')

    @include('boarding::layouts.component.index_manage_submission', ['module' => 'counseling'])
@endsection
