@extends('web::layouts.about', ['headerTitle' => request()->bahasa == 'id' ? 'Laporan' : 'Annual Report'])
@section('title', request()->bahasa == 'id' ? 'Laporan' : 'Annual Report'.' | ')

@php
    $reports = [
        [
            'title' => 'Nisi ut ut velit enim quis',
            'description' => 'Et nisi ipsum Lorem culpa cupidatat aliquip officia tempor sit elit velit sit.',
            'date' => 'Agustus 6, 2021',
            'imageUrl' => '/images/rand-2.jpg',
        ],
        [
            'title' => 'Ex do ex adipisicing consectetur',
            'description' => 'Anim exercitation dolore consectetur reprehenderit ex dolor sunt dolore.',
            'date' => 'Agustus 6, 2021',
            'imageUrl' => '/images/rand-3.jpg',
        ],
        [
            'title' => 'Quis pariatur aliqua non.',
            'description' => 'Magna ullamco ex anim occaecat pariatur laboris adipisicing incididunt non irure.',
            'date' => 'Agustus 6, 2021',
            'imageUrl' => '/images/rand-1.jpeg',
        ],
    ];
@endphp

@section('content')
    <section class="container mx-auto md:px-16 py-16 space-y-8">
         @include('web::partials.section-header', [
            'title' => [
                [
                    'content' => explode(' ', get_content_json($section_report)[$lang]['title'])[0],
                    'isBold' => true
                ],
                ['content' => explode(' ', get_content_json($section_report)[$lang]['title'])[1].' '.(isset(explode(' ', get_content_json($section_report)[$lang]['title'])[2]) ? explode(' ', get_content_json($section_report)[$lang]['title'])[2] : '')]
            ],
            'description' => get_content_json($section_report)[$lang]['post0']
        ])

        <div class="grid grid-cols-3 gap-4">
            @foreach ($content_report as $key => $val)
                <div class="flex flex-col bg-background border border-border shadow-sm rounded-lg">
                    <div class="p-4 md:p-5">
                        <h3 class="text-lg font-bold">
                            {{ get_content_json($val)[$lang]['title'] }}
                        </h3>
                        <p class="mt-2 text-muted-foreground">
                            {{ substr(get_content_json($val)[$lang]['post0'], 0, 90) }} ...
                        </p>
                        <x-web::button variant="link" size="xs" class="mt-4 gap-2">
                            Lihat
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-4" stroke="currentColor"
                                fill="currentColor">
                                <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" />
                            </svg>
                        </x-web::button>
                    </div>
                    <div class="bg-muted border-t rounded-b-lg py-3 px-4 md:py-2 md:px-5 border-border">
                        <p class="text-sm text-muted-foreground">
                            {{ tgl_indo(date('Y-m-d', strtotime($val->created_at))) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @include('web::about.partials.financial-sources-section', [
        'caption' => $source_founding_section,
        'fund_1' => $fund_1,
        'fund_2' => $fund_2,
        'fund_3' => $fund_3,
        'lang' => $lang
    ])
@endsection
