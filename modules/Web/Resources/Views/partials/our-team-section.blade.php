<section @if (isset($id)) id="{{ $id }}" @endif
    class="container mx-auto md:px-16 py-16 space-y-8">
    @include('web::partials.section-header', [
        'title' => [
            ['content' => $our_team, 'isBold' => true],
            // [
            //     'content' =>
            //         (isset(explode(' ', get_content_json($our_team)[$lang]['title'])[1])
            //             ? explode(' ', get_content_json($our_team)[$lang]['title'])[1]
            //             : '') .
            //         ' ' .
            //         (isset(explode(' ', get_content_json($our_team)[$lang]['title'])[2])
            //             ? explode(' ', get_content_json($our_team)[$lang]['title'])[2]
            //             : ''),
            // ],
        ],
        // 'description' => get_content_json($our_team)[$lang]['post0'],
    ])
    <div class="flex gap-4 w-full flex-wrap justify-center">
        @foreach ($our_team_content as $key => $val)
            <div class="group md:w-52">
                <div class="relative">
                    <img src="{{ get_image($val->location, $val->image) }}" alt="team-member-1"
                        class="rounded-lg object-cover h-60 md:h-80 md:w-52" />
                    {{-- <div
                        class="flex group-hover:opacity-100 opacity-0 transition-opacity duration-500 items-center justify-center absolute bottom-0 inset-x-0 pb-2 pt-20 rounded-b-lg bg-gradient-to-t from-gray-950 via-gray-950/50 to-transparent">
                        <a href="#" target="_blank" class="block">
                            <x-web::button variant="ghost" size="icon"
                                class="hover:bg-gray-600/40 hover:text-white text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </x-web::button>
                        </a>
                    </div> --}}
                </div>
                <h3 class="text-xl text-center font-bold">
                    {{ get_content_json($val)[$lang]['title'] }}
                </h3>
                <p class="text-center text-sm text-muted-foreground">
                    Job Title
                </p>
            </div>
        @endforeach
    </div>
</section>
