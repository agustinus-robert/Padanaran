@extends('web::layouts.about', ['headerTitle' => request()->bahasa == 'id' ? 'Sejarah' : 'History'])
@section('title', request()->bahasa == 'id' ? 'Sejarah' : 'History' . ' | ')

@php
    $timelineItems = [
        [
            'title' => 'Excepteur dolore tempor quis deserunt.',
            'date' => 'Desember 2, 2021',
            'description' =>
                'Cupidatat excepteur incididunt laboris voluptate nostrud deserunt. Nulla aute ullamco dolore aliquip eu sit.',
        ],
        [
            'title' => 'Ad labore aliquip est sint anim laborum ex.',
            'date' => 'Desember 2, 2021',
            'description' =>
                'Dolor irure eu ullamco laborum enim. Eiusmod in sit aute occaecat veniam sunt cupidatat sunt exercitation veniam.',
        ],
        [
            'title' => 'Fugiat sit nisi ullamco amet enim.',
            'date' => 'Desember 2, 2021',
            'description' => 'Do incididunt in aliquip eiusmod. Dolore proident enim nisi aliquip dolore esse irure.',
        ],
    ];
@endphp

@section('content')
    <section class="container mx-auto md:px-16 py-16 space-y-8">
        @include('web::partials.section-header', [
            'title' => [
                [
                    'content' => explode(' ', get_content_json($section_transformasi)[$lang]['title'])[0],
                    'isBold' => true,
                ],
                ['content' => explode(' ', get_content_json($section_transformasi)[$lang]['title'])[1]],
            ],
        ])

        <ol class="items-center sm:flex">
            @foreach ($content_transformasi as $key => $val)
                <li class="relative w-full mb-6 sm:mb-0">
                    <div class="flex items-center">
                        <div
                            class="z-10 flex items-center justify-center w-6 h-6 bg-primary rounded-full ring-0 ring-white sm:ring-8 dark:ring-gray-900 shrink-0">
                            <svg class="w-2.5 h-2.5 text-primary-foreground" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <div class="hidden sm:flex w-full bg-border h-0.5"></div>
                    </div>
                    <div class="mt-3 sm:pe-8">
                        <h3 class="text-lg font-semibold">
                            {{ get_content_json($val)[$lang]['title'] }}
                        </h3>
                        <time
                            class="block mb-2 text-sm font-normal leading-none text-muted-foreground">{{ get_content_json($val)[$lang]['post0'] }}</time>
                        <p class="text-base font-normal text-foreground/80">{{ get_content_json($val)[$lang]['post1'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="container mx-auto md:px-16 py-16 space-y-8">
        @include('web::partials.section-header', [
            'title' => [['content' => get_content_json($section_sejarah)[$lang]['title']]],
        ])
        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-4">
                <h4 class="font-bold">{{ get_content_json($section_sejarah)[$lang]['title'] }}</h4>
                 
                {!! str_replace("<p>", "<p class='text-justify'>", preg_replace('/<span[^>]+\>/i', '', get_content_json($content_sejarah)[$lang]['post0'])) !!}
                 
            </div>
            <img src="{{ get_image($section_sejarah->location, $section_sejarah->image) }}" alt="Sejarah YSBY"
                class="object-cover object-center rounded-lg w-full">
        </div>
        {{-- <div>
            <p>Sint aute proident elit do et do anim ex dolore labore laboris ea velit nisi. Occaecat magna ex ut culpa
                aliqua
                culpa elit qui nulla non tempor qui sint. Proident anim ad cillum laborum Lorem adipisicing reprehenderit.
                Sit
                qui ipsum adipisicing aliqua nulla dolor elit sint. Aliquip aliqua ea esse pariatur qui occaecat proident
                sint
                Labore et anim amet ipsum esse incididunt dolore. Officia veniam reprehenderit
            </p>
            <ol type="a">
                <li>Ut ullamco do cupidatat anim. Duis irure fugiat laboris occaecat. Fugiat non deserunt incididunt id qui
                    sit
                    do do ipsum et. Dolor labore ea voluptate sit. Laboris velit sit exercitation deserunt. Veniam sit
                    eiusmod
                    fugiat adipisicing voluptate nostrud ut nisi adipisicing.
                </li>
                <li>Sunt sit in irure adipisicing aute culpa laboris mollit occaecat. Sunt velit dolore aute laborum ipsum
                    est
                    ut adipisicing veniam minim velit ut minim non. Culpa proident elit eiusmod commodo. Mollit mollit sunt
                    veniam magna aute occaecat magna nostrud esse sit cupidatat duis laboris. Do et minim velit enim
                    occaecat
                    voluptate reprehenderit nostrud. Sint ullamco consequat incididunt sint minim in incididunt amet ut duis
                    et.
                    Ex exercitation labore cupidatat ipsum ut tempor incididunt dolore labore dolore.
                </li>
            </ol>
        </div> --}}
    </section>
    @include('web::partials.organizations', [
        'section' => $section_company,
        'content' => $content_company,
        'lang' => $lang,
    ])
@endsection
