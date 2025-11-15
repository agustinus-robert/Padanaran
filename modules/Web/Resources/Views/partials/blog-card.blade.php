@props(['blog', 'isCompact' => false])

@if ($isCompact)
    <a href="{{ url($lang . '/blog/' . get_content_json($blog)[$lang]['slug']) }}">
        <div
            class="p-2 rounded-md border border-border flex items-center gap-4 justify-between hover:shadow transition-shadow">
            <h4 class="font-medium line-clamp-2 flex-1">
                {!! get_content_json($blog)[$lang]['title'] !!}
                Nulla proident cillum proident dolor ut eiusmod ad laborum duis.
            </h4>
            <div class="flex items-center gap-2">
                <x-web::badge> Test </x-web::badge>
                <span class="text-muted-foreground text-sm">
                    {!! tgl_indo(date('Y-m-d', strtotime($blog->created_at))) !!}
                </span>
            </div>
        </div>
    </a>
@else
    <a href="{{ url($lang . '/blog/' . get_content_json($blog)[$lang]['slug']) }}">
        <div
            class="p-4 rounded-lg border border-border flex gap-4 flex-col group hover:shadow-md transition-shadow h-full">
            <div class="w-full h-56 overflow-hidden rounded-lg">
                <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out object-cover rounded-lg"
                    src="{{ get_image($blog->location, $blog->image) }}" alt="Blog Image">
            </div>

            @if (!empty(category_by_post_id($blog->id)->title))
                <x-web::badge> {{ category_by_post_id($blog->id)->title }} </x-web::badge>
            @else
                <x-web::badge> Uncategory </x-web::badge>
            @endif

            <h4 class="text-xl font-bold line-clamp-2">
                {!! get_content_json($blog)[$lang]['title'] !!}
            </h4>
            <p class="line-clamp-3 text-sm">
                {!! strlen(get_content_json($blog)[$lang]['post0']) > 100
                    ? substr(get_content_json($blog)[$lang]['post0'], 0, 100) . ' ...'
                    : get_content_json($blog)[$lang]['post0'] !!}
            </p>

            <span class="text-muted-foreground text-sm mt-auto">
                {!! tgl_indo(date('Y-m-d', strtotime($blog->created_at))) !!}
            </span>
        </div>
    </a>
@endif
