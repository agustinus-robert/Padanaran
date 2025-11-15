@extends('web::layouts.about', ['headerTitle' => request()->bahasa == 'id' ? 'Visi & Misi' : 'Vision & Mission'])
@section('title', request()->bahasa == 'id' ? 'Visi & Misi' : 'Vision & Mission' . ' | ')

@section('content')
     <section class="container mx-auto md:px-16 py-16 space-y-8">
        @php
           $exp_captions = explode(' ', get_content_json($caption_vision_mission)[$lang]['title']);

           $caption_vision1 = $exp_captions[0].' '.$exp_captions[1];

           $caption_vision2 = $exp_captions[2].' '.(isset($exp_captions[3]) ? $exp_captions[3] : '');
        @endphp

        @include('web::partials.section-header', [
            'title' => [
                [
                    'content' => $caption_vision1,
                    'isBold' => true,
                ],
                [
                    'content' => $caption_vision2
                ],
            ],
            'description' => get_content_json($caption_vision_mission)[$lang]['post0'],
        ])

        @include('web::about.partials.vission-n-mission-section')
    </section>

    <section class="py-16 bg-primary">
        @include('web::partials.partners-section', [
            'gradientFromClass' => 'from-primary',
            'isDarkBackground' => true,
        ])
    </section>
@endsection
