@extends('web::layouts.default')
@section('title', 'Program | ')

@section('main')
    <header class="container mx-auto md:px-16 py-16">
        <div class="relative md:mb-0 mb-8">
            <div
                class="absolute -left-6 md:-left-20 h-[1px] w-4 md:w-16 bg-primary dark:bg-primary-500 top-1/2 translate-y-1/2">
            </div>
            <span class="text-primary-text font-bold text-xs">{{ get_content_json($caption_program)[$lang]['title'] }}</span>
        </div>
        <div class="grid md:grid-cols-2 place-items-center gap-4 md:gap-16">
            <div class="space-y-4">
                <h1 class="text-4xl md:text-5xl font-bold">{{ get_content_json($caption_program)[$lang]['post0'] }}</h1>
                {!! strip_tags(get_content_json($caption_program)[$lang]['post1']) !!}
            </div>
            <img src="{{ get_image($caption_program->location, $caption_program->image) }}" alt="Our Works"
                class="object-cover w-full h-full rounded-lg">
        </div>
    </header>
    <section class="bg-primary">
        <div class="container mx-auto md:px-16 py-16 space-y-8">
            @include('web::partials.section-header', [
                'title' => [['content' => get_content_json($caption_area_program)[$lang]['title']]],
                'isDarkBackground' => true,
            ])
            <div class="flex justify-between gap-4">
                @foreach ($content_area_program as $key => $val)
                    <div class="flex items-center justify-center flex-col">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-28 text-primary-foreground"
                            fill="currentColor" stroke="currentColor">
                            <path
                                d="M19 3H18V1H16V3H8V1H6V3H5C3.9 3 3 3.9 3 5V19C3 20.11 3.9 21 5 21H19C20.11 21 21 20.11 21 19V5C21 3.9 20.11 3 19 3M19 19H5V9H19V19M5 7V5H19V7H5M10.56 17.46L16.5 11.53L15.43 10.47L10.56 15.34L8.45 13.23L7.39 14.29L10.56 17.46Z" />
                        </svg>
                        <span
                            class="text-primary-foreground font-semibold">{{ get_content_json($val)[$lang]['title'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="container mx-auto md:px-16 py-16 space-y-8">
        @include('web::partials.section-header', [
            'title' => [
                ['content' => get_content_json($caption_program)[$lang]['title']],
                ['content' => '', 'isBold' => true],
            ],
        ])
        <h2 class="text-2xl font-bold">{{ $lang == 'id' ? 'Program sebelumnya' : 'Previous Program' }}</h2>
        <div class="grid md:grid-cols-3 gap-4">
            @foreach ($program_event as $key => $program)
                @if (isset($arr_category[$program->id]))
                    @include('web::partials.program-card', [
                        'program' => $program,
                        'tag' => $arr_category[$program->id],
                        'lang' => $lang,
                    ])
                @endif
            @endforeach
        </div>
        {{-- <x-web::button class="mx-auto" variant="outline">Lihat Semua</x-web::button> --}}
        <div class="w-full flex justify-center items-center">
            @if(count($program_event) > 4)
                {{ $program_event->links('pagination::tailwind') }}
            @endif  
        </div>
    </section>
    <section class="container mx-auto md:px-16 py-16 space-y-8">
        <div class="flex items-center gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-8" fill="currentColor"
                stroke="currentColor">
                <path
                    d="M19 3H18V1H16V3H8V1H6V3H5C3.9 3 3 3.9 3 5V19C3 20.11 3.9 21 5 21H19C20.11 21 21 20.11 21 19V5C21 3.9 20.11 3 19 3M19 19H5V9H19V19M5 7V5H19V7H5M10.56 17.46L16.5 11.53L15.43 10.47L10.56 15.34L8.45 13.23L7.39 14.29L10.56 17.46Z" />
            </svg>
            <h2 class="text-3xl font-bold">{{ get_content_json($upcoming_event)[$lang]['title'] }}</h2>
        </div>
        <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8">
            @foreach ($upcoming_event_konten as $key => $value)
                @if (isset($arr_category[$value->id]))
                    @if ($arr_category[$value->id] == 0)
                        @include('web::partials.event-card', [
                            'date' => date('d', strtotime(get_content_json($value)[$lang]['post1'])),
                            'month' => date('m', strtotime(get_content_json($value)[$lang]['post1'])),
                            'title' => get_content_json($value)[$lang]['title'],
                            'class' => 'w-full',
                        ])
                        <hr class="border-border w-24">
                    @else
                        @include('web::partials.event-card', [
                            'date' => date('d', strtotime(get_content_json($value)[$lang]['post1'])),
                            'month' => date('m', strtotime(get_content_json($value)[$lang]['post1'])),
                            'title' => get_content_json($value)[$lang]['title'],
                            'class' => 'w-full',
                        ])
                    @endif
                @endif
            @endforeach
        </div>
    </section>
    @include('web::partials.join-us-section', [
        'join_us' => $join_us,
        'lang' => $lang,
    ])
    @include('web::partials.contact-us-section')
    @include('web::partials.map-section')
@endsection
