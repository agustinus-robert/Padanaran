@extends('portal::layouts.user')

@extends('portal::layouts.components.navbar-user')

@section('title', 'User')

@section('navtitle', 'User')

@section('content')
	@livewire('portal::payments', ['invoice_id' => @$invoice])
@endsection