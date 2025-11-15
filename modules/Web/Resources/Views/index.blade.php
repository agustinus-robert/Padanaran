@extends('web::layouts.default')

@push('style')
<style>
	.bg-web {
		background: url('{{ asset('img/bg/web.jpg') }}') no-repeat center;
		background-size: cover;
	}
</style>
@endpush

@section('content')
	<div class="jumbotron bg-web text-white rounded-0">
		<div class="container py-5 my-5">
			<h1>{!! config('web.jumbotron.title') !!}</h1>
			<p>{!! config('web.jumbotron.subtitle') !!}</p>
		</div>
	</div>
@endsection
