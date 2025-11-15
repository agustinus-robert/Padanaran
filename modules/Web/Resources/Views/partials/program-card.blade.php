<div class="p-6 rounded-lg bg-cover flex flex-col justify-between gap-8"
    style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ get_image($program->location, $program->image) }}');">
    <div class="space-y-8">
        <h4 class="text-2xl font-bold text-white">
            {{ get_content_json($program)[$lang]['title'] }}
        </h4>
        <div class="space-y-2">
            <x-web::badge class="block">{{ category_by_post_id($program->id)->title }}</x-web::badge>
            
                {!! (strlen(get_content_json($program)[$lang]['post2']) > 100) ? substr(str_replace('<p>', '<p class="text-sm text-white">',get_content_json($program)[$lang]['post2']), 0, 100).' ...' : str_replace('<p>', '<p class="text-sm text-white">',get_content_json($program)[$lang]['post2']) !!}
        </div>
    </div>
    <a href="{{ url($lang . '/works/' . get_content_json($program)[$lang]['slug']) }}" class="block w-full">
    <x-web::button variant="outline" size="sm"
        class="text-white border-white dark:hover:border-transparent dark:border-white hover:dark:bg-white hover:dark:text-black">Selengkapnya</x-web::button>
    </a>
</div>
