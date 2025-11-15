@extends('admin::layouts.admin')

@extends('admin::layouts.components.navbar-admin')

@section('title', 'Order')

@section('navtitle', 'Order')

@section('content')
    @livewire('admin::builder.order') 
@endsection
