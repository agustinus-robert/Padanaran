@extends('web::layouts.default', [
    'useTransparentNavbar' => true,
])
@section('title', 'Detail Kegiatan | ')

@section('main')
    <div class="relative">
        <div class="-z-20 bg-cover bg-center h-[50vh] absolute top-0 w-full"
            style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ get_image($event_detail->location, $event_detail->image) }}');">
        </div>
        <div class="container mx-auto space-y-8 mb-16">
            <header class="flex items-center pt-[10vh]">
                <div class="space-y-2 max-w-4xl">
                    <p class="text-gray-200 font-medium">Detail Kegiatan</p>
                    <h1 class="font-bold text-white text-5xl">{{get_content_json($event_detail)[$lang]['title']}}
                    </h1>
                    {{-- <p class="text-gray-200">Officia cupidatat nostrud nulla sint reprehenderit anim dolor do duis ut
                        dolore
                        esse
                        Lorem occaecat ad
                    </p> --}}
                </div>
            </header>
            <div
                class="bg-background dark:bg-muted shadow container mx-auto py-8 rounded-lg prose prose-sm lg:prose-base prose-primary dark:prose-invert max-w-none">
                <div class="flex items-center gap-2">
                    <span>
                        Dari <a href="#" class="text-primary-600 dark:text-primary-500">YSBY</a></span>
                    <div class="size-2 bg-foreground/15 rounded-full"></div>
                    {!! tgl_indo(date('Y-m-d', strtotime($event_detail->created_at))).' '.date('H:i', strtotime($event_detail->created_at)) !!}
                </div>
                {!! get_content_json($event_detail)[$lang]['post2'] !!}
            </div>
        </div>
        <aside aria-label="Related articles" class="py-8 lg:py-24 bg-muted">
            <div class="px-4 mx-auto max-w-screen-xl">
                <h2 class="mb-8 text-2xl font-bold ">Kegiatan Terkait</h2>
                <div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($link_related as $key => $article)
                        <article class="max-w-xs">
                            <a href="{{ url($lang . '/event-n-news/' . get_content_json($article)[$lang]['slug']) }}">
                                <img src="{{ get_image($article->location, $article->image) }}" class="mb-5 rounded-lg" alt="Image 1">
                            </a>
                            <p class="text-xs mb-1 text-muted-foreground">{{tgl_indo(date('Y-m-d', strtotime($article->created_at)))}}</p>
                            <h2 class="mb-2 text-xl font-bold leading-tight ">
                                <a href="{{ url($lang . '/event-n-news/' . get_content_json($article)[$lang]['slug']) }}">
                                   {!! substr(get_content_json($article)[$lang]['title'], 0, 100) !!} 
                                </a>
                            </h2>
                            <p class="mb-4 text-gray-500 dark:text-gray-400 line-clamp-3">
                                {!! (strlen(get_content_json($article)[$lang]['post2']) > 100) ? substr(get_content_json($article)[$lang]['post2'], 0, 100).' ...' : get_content_json($article)[$lang]['post2'] !!}
                            </p>
                            <a href="{{ url($lang . '/event-n-news/' . get_content_json($article)[$lang]['slug']) }}"
                                class="inline-flex items-center font-medium underline underline-offset-4 text-primary-600 dark:text-primary-500 hover:no-underline">
                                Baca selengkapnya
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
@endsection
