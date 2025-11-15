@extends('web::layouts.default')
@section('title', 'Blog | ')

@section('main')
    <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 antialiased">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
            <article class="mx-auto w-full max-w-none prose prose-sm lg:prose-base prose-primary dark:prose-invert">
                <header class="mb-4 lg:mb-6 not-prose">
                    <address class="flex items-center mb-6 not-italic">
                        <div class="inline-flex items-center mr-3 text-sm ">
                            <img class="mr-4 w-16 h-16 rounded-full object-cover" src="/images/person.jpg" alt="Jane Doe">
                            <div>
                                <a href="#" rel="author" class="text-xl font-bold ">Admin YSBY</a>
                                <p class="text-base text-gray-500 dark:text-gray-400">Content By YSBY
                                </p>
                                <p class="text-base text-gray-500 dark:text-gray-400"><time pubdate datetime="2022-02-08"
                                        title="February 8th, 2022">{{tgl_indo(date('Y-m-d', strtotime($blog_detail->created_at)))}}</time></p>
                            </div>
                        </div>
                    </address>
                    <h1
                        class="mb-4 text-3xl font-extrabold leading-tight text-gray-900 lg:mb-6 lg:text-4xl dark:text-white">
                        {{get_content_json($blog_detail)[$lang]['title']}}</h1>
                </header>
                {{-- <p class="lead"> Sit officia non ad culpa pariatur consequat culpa. Enim amet cupidatat labore est velit
                    ea ipsum magna ad. Commodo Lorem ipsum aute quis enim deserunt nisi enim. Duis occaecat incididunt est
                    Lorem proident excepteur qui quis voluptate. Aliquip et aute laborum do excepteur.</p>
                <p>Do nisi ex ad ex. Ex eiusmod id eiusmod laboris sit esse veniam pariatur tempor ipsum tempor. Ullamco qui
                    enim cupidatat non in laboris aliqua. Qui do laboris officia Lorem sit nisi deserunt ad ut ad.</p>
                <p>
                    Eu elit laboris esse labore cupidatat proident aliqua ad laboris. Velit sit aute tempor qui labore ex.
                    Ullamco sint reprehenderit id reprehenderit pariatur pariatur ad amet tempor nostrud. Nisi labore id
                    cillum eiusmod deserunt laboris dolor elit aute in voluptate ut velit. Nulla sint mollit consequat non.
                    Nisi enim fugiat aliquip excepteur consectetur Lorem mollit excepteur.
                </p> --}}
                <figure><img src="{{ get_image($blog_detail->location, $blog_detail->image) }}"
                        alt="">
                    <figcaption>Digital art by Anonymous</figcaption>
                </figure>
                        {!! get_content_json($blog_detail)[$lang]['post0'] !!}
                    </article>
                </section>
            </article>
        </div>
    </main>

    @if(count($link_related) > 0)
        <aside aria-label="Related articles" class="py-8 lg:py-24 bg-muted">
            <div class="px-4 mx-auto max-w-screen-xl">
                <h2 class="mb-8 text-2xl font-bold ">{{$lang == 'id' ? 'Artikel Terkait' : 'Related Article'}}</h2>
                <div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($link_related as $key => $article)
                        <article class="max-w-xs">
                            <a href="{{ url($lang . '/blog/' . get_content_json($article)[$lang]['slug']) }}">
                                <img src="{{ get_image($article->location, $article->image) }}" class="mb-5 rounded-lg" alt="Image 1">
                            </a>
                            <h2 class="mb-2 text-xl font-bold leading-tight ">
                                <a href="{{ url($lang . '/blog/' . get_content_json($article)[$lang]['slug']) }}">
                                    {{ $article['title'] }}
                                </a>
                            </h2>
                            <p class="mb-4 text-gray-500 dark:text-gray-400 line-clamp-3">
                                {!! (strlen(get_content_json($article)[$lang]['post0']) > 100) ? substr(get_content_json($article)[$lang]['post0'], 0, 100).' ...' : get_content_json($article)[$lang]['post0'] !!}
                            </p>
                            <a href="{{ url($lang . '/blog/' . get_content_json($article)[$lang]['slug']) }}"
                                class="inline-flex items-center font-medium underline underline-offset-4 text-primary-600 dark:text-primary-500 hover:no-underline">
                                Baca selengkapnya
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </aside>
    @endif
@endsection
