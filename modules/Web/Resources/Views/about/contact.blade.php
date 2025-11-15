@extends('web::layouts.about', ['headerTitle' => request()->bahasa == 'id' ? 'Hubungi Kami' : 'Call Us'])
@section('title', request()->bahasa == 'id' ? 'Hubungi Kami' : 'Call Us'.' | ')

@section('content')
    <section class="container mx-auto md:px-16 py-16 space-y-8">
        @include('web::partials.section-header', [
            'title' => [
                ['content' => explode(' ', get_content_json($content_call_us)[$lang]['title'])[0], 'isBold' => true], 
                ['content' => explode(' ', get_content_json($content_call_us)[$lang]['title'])[1]]
            ],
        ])
        <div class="grid md:grid-cols-[30%,70%] gap-4 md:gap-8 lg:gap-16">
            <div class="space-y-2">
                <h2 class="text-5xl font-bold text-primary-text">
                    {{get_content_json($content_call_us)[$lang]['post0']}}
                </h2>
                {!!get_content_json($content_call_us)[$lang]['post1']!!}
            </div>
            @livewire('web::global.contact-form')
        </div>
    </section>
    @include('web::partials.join-us-section', [
        'join_us' => $join_us,
        'lang' => $lang
    ])
@endsection
