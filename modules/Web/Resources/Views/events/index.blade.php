@extends('web::layouts.default')
@section('title', 'Kegiatan & Berita | ')

@section('main')
    <header class="container mx-auto md:px-16 py-16">
        <div class="relative md:mb-0 mb-8">
            <div
                class="absolute -left-6 md:-left-20 h-[1px] w-4 md:w-16 bg-primary dark:bg-primary-500 top-1/2 translate-y-1/2">
            </div>
            <span class="text-primary-text font-bold text-xs">{{ get_content_json($caption)[$lang]['title'] }}</span>
        </div>
        <div class="grid md:grid-cols-2 place-items-center gap-4 md:gap-16">
            <div class="space-y-4">
                <h1 class="text-4xl md:text-5xl font-bold">{{ get_content_json($caption)[$lang]['post0'] }}</h1>
                {!! strip_tags(get_content_json($caption)[$lang]['post1']) !!}
            </div>
            <img src="{{ get_image($caption->location, $caption->image) }}" alt="Our Works"
                class="object-cover w-full h-full rounded-lg">
        </div>
    </header>
    <section class="container mx-auto md:px-16 py-16 space-y-8">
        @include('web::partials.section-header', [
            'title' => [
                [
                    'content' =>
                        explode(' ', get_content_json($caption)[$lang]['title'])[0] .
                        ' ' .
                        explode(' ', get_content_json($caption)[$lang]['title'])[1],
                    'isBold' => true,
                ],
                ['content' => explode(' ', get_content_json($caption)[$lang]['title'])[2]],
            ],
        ])
        <div class="grid md:grid-cols-2 gap-4">
            @foreach ($program_event as $key => $val)
                @if (isset($arr_category[$val->id]))
                    <a class="flex flex-col md:flex-row items-center justify-center md:gap-4 border border-border rounded-lg hover:shadow-sm focus:outline-none"
                        href="{{ url($lang . '/event-n-news/' . get_content_json($val)[$lang]['slug']) }}">
                        <img class="w-full md:w-48 h-full object-cover rounded-lg md:rounded-s-lg"
                            src="{{ get_image($val->location, $val->image) }}" alt="Event Image">
                        <div class="flex flex-col justify-center gap-1 p-4 md:p-0 w-full">
                            <span class="text-xs text-muted-foreground flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-3"
                                    fill="currentColor" stroke="currentColor">
                                    <path
                                        d="M19 3H18V1H16V3H8V1H6V3H5C3.89 3 3 3.9 3 5V19C3 20.11 3.9 21 5 21H19C20.11 21 21 20.11 21 19V5C21 3.9 20.11 3 19 3M19 19H5V9H19V19M19 7H5V5H19V7Z" />
                                </svg>
                                {{ tgl_indo(date('Y-m-d', strtotime(get_content_json($val)[$lang]['post0']))) }}
                            </span>
                            <h3 class="font-semibold text-sm">
                                {{ get_content_json($val)[$lang]['title'] }}
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                {!! (strlen(get_content_json($val)[$lang]['post2']) > 100) ? substr(get_content_json($val)[$lang]['post2'], 0, 100).' ...' : get_content_json($val)[$lang]['post2'] !!}
                            </p>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
        <div class="w-full flex justify-center items-center">
            <x-web::button class="mx-auto" variant="outline">
            {{ $program_event->links('pagination::tailwind') }}
            </x-web::button>
        </div>
    </section>
    @include('web::partials.join-us-section', [
        'join_us' => $join_us,
        'lang' => $lang,
    ])
    @include('web::partials.contact-us-section')
    @include('web::partials.map-section')
@endsection
