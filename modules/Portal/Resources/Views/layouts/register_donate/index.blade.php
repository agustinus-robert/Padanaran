@extends('donation::layouts.user')

@extends('donation::layouts.components.navbar-user')

@section('title', 'Registrasi Donator')

@section('navtitle', 'Registraasi Donator')

@section('content')

@livewire('donation::donate.register-donation')

@endsection
