@extends('web::layouts.about', ['headerTitle' => request()->bahasa == 'id' ? 'Bergabung dengan kami' : 'Partnership'])
@section('title', request()->bahasa == 'id' ? 'Bergabung dengan kami' : 'Partnership'.' | ')

@section('content')
     <section class="py-16 bg-primary">
        @include('web::partials.partners-section', [
            'gradientFromClass' => 'from-primary',
            'isDarkBackground' => true,
        ])
    </section>
@endsection
