@extends('admin::layouts.admin')

@extends('admin::layouts.components.navbar-admin')

@section('title', 'Site Config')

@section('navtitle', 'Site Config')

@section('content')

@livewire('admin::configure.sites-config')

@endsection