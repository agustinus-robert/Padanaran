@extends('admin::layouts.admin')

@extends('admin::layouts.components.navbar-admin')

@section('title', 'Posting Form')

@section('navtitle', 'Posting Form')

@section('content')

@livewire('admin::builder.form')

@endsection