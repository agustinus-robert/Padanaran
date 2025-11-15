<section class="container mx-auto md:px-16 py-16 space-y-8">
    @include('web::partials.section-header', [
        'title' => [
            ['content' => explode(' ', get_content_json($caption)[$lang]['title'])[0] . ' & ', 'isBold' => true],
            ['content' => explode(' ', get_content_json($caption)[$lang]['title'])[2]],
        ],
        'description' => get_content_json($caption)[$lang]['post0'],
    ])
    <div class="grid md:grid-cols-3 gap-4">
        @foreach ($program_event as $key => $val)
            @if (isset($category_choosen[$val->id]))
                @include('web::partials.program-card', [
                    'program' => $val,
                    'tag' => $category_choosen[$val->id],
                    'lang' => $lang,
                ])
            @endif
        @endforeach
    </div>
    @if (count($programs) > 3)
        <div class="w-full flex justify-center items-center">
            <x-web::button class="mx-auto" variant="outline">Lihat Semua</x-web::button>
        </div>
    @endif
</section>
